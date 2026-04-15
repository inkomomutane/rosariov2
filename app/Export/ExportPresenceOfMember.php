<?php

        namespace App\Export;

        use App\Models\User;
        use Carbon\Carbon;
        use Illuminate\Database\Query\Builder;
        use Maatwebsite\Excel\Concerns\Exportable;
        use Maatwebsite\Excel\Concerns\FromQuery;
        use Maatwebsite\Excel\Concerns\WithColumnWidths;
        use Maatwebsite\Excel\Concerns\WithCustomStartCell;
        use Maatwebsite\Excel\Concerns\WithEvents;
        use Maatwebsite\Excel\Concerns\WithHeadings;
        use Maatwebsite\Excel\Concerns\WithMapping;
        use Maatwebsite\Excel\Concerns\WithStyles;
        use PhpOffice\PhpSpreadsheet\Style\Alignment;
        use PhpOffice\PhpSpreadsheet\Style\Border;
        use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
        use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
        use Maatwebsite\Excel\Concerns\WithDrawings;
        use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

        class ExportPresenceOfMember implements
            FromQuery, WithStyles, WithHeadings, WithDrawings, WithCustomStartCell,
            WithColumnWidths, WithEvents, WithMapping
        {
            use Exportable;

            private int $currentRow = 13; // Starting row for data

            public function __construct(
                public Carbon $month
            ) {
            }

            public function query()
            {
                $start = $this->month->copy()->startOfMonth();
                $end = $this->month->copy()->endOfMonth();

                return User::with(['presenceRegisters' => function ($query) use ($start, $end) {
                    $query->whereBetween('date', [$start, $end]);
                },'patent']);
            }

            public function map($user): array
            {
                $start = $this->month->copy()->startOfMonth();
                $end = $this->month->copy()->endOfMonth();

                $days = collect();
                $period = \Carbon\CarbonPeriod::create($start, $end);

                foreach ($period as $day) {
                    $presence = $user->presenceRegisters->firstWhere('date', $day->toDateString());
                    $days->push($presence ? 'P' : 'F');
                }

                $currentRow = $this->currentRow + 1; // Increment for the current row
                $result = array_merge([
                    'member' => $user->name,
                    'NIP' => $user->nip,
                    'Patente' => $user?->patent?->title, // Add patente field if available
                ], $days->toArray(), [
                    'Faltas' => "=COUNTIF(D{$currentRow }:AH{$currentRow}, \"F\")"
                ]);

                $this->currentRow++; // Increment for next row

                return $result;
            }

            public function headings(): array
            {
                $headings = [
                    'Membro',
                    'NIP',
                    'Patente',
                ];
                $period = \Carbon\CarbonPeriod::create($this->month->copy()->startOfMonth(), $this->month->copy()->endOfMonth());
                foreach ($period as $day) {
                    $headings[] = $day->day;
                }
                $headings[] = 'Total'; // Add Total column header
                return $headings;
            }

            public function styles(Worksheet $sheet)
            {
                $sheet->getStyle('A1:AI2')->getFont()->setBold(true);

                # set borders
                $sheet->getStyle('a12:ai' . User::count() + 13)->getBorders()->applyFromArray([
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => '000000'],
                    ],
                ]);
            }

            public function drawings()
            {
                $drawing = new Drawing();
                $drawing->setName('PRM Logo');
                $drawing->setDescription('PRM Logo');
                $drawing->setPath(resource_path('js/images/moz_emb.png'));
                $drawing->setHeight(50);
                $drawing->setCoordinates('M2');
                return $drawing;
            }

            public function startCell(): string
            {
                return 'A13';
            }

            public function columnWidths(): array
            {
                return array_merge([
                    'A' => 30,
                    'B' => 18,
                    'C' => 10,
                ], $this->generateColumnRange('D', 'AH'), [
                    'AI' => 8 // Width for Total column
                ]);
            }

            private function generateColumnRange($start = 'A', $end = 'AH'): array
            {
                $columns = [];
                $current = $start;

                while ($current !== $end) {
                    $columns[$current] = 4;
                    $current++;
                }
                $columns[$end] = 4; // Include the end column

                return $columns;
            }

            public function registerEvents(): array
            {
                return [\Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                    // Add "Republic de Mocambique" text on line 8
                    $event->sheet->setCellValue('A6', 'REPÚBLICA DE MOCAMBIQUE');
                    $event->sheet->setCellValue('A7', 'MINISTÉRIO DO INTERIOR');
                    $event->sheet->setCellValue('A8', 'COMANDO PROVINCIAL DA PRM - SOFALA');
                    $event->sheet->setCellValue('A9', 'DEPARTAMENTO DE ADMINSTRAÇÃO E FINANÇAS');
                    $event->sheet->setCellValue('A10', 'REPARTIÇAO DE VENCIMENTO E ABONOS');

                    $event->sheet->setCellValue('A12', 'MAPA DE EFECTIVIDADE REFERENTE AO MÊS DE ' . $this->month->format('F Y') . '- POLICIAS - MINT');

                    // Optional: Merge cells to center the text across multiple columns
                    for ($i = 6; $i <= 12; $i++) {
                        $event->sheet->mergeCells("A$i:AI$i");
                        $event->sheet->getStyle("A$i")->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    $event->sheet->getPageSetup()
                        ->setPaperSize(PageSetup::PAPERSIZE_A3);

                    $event->sheet->getPageSetup()
                        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                }];
            }
        }
