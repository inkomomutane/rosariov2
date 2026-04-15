<?php
namespace App\Http\Controllers\MemberInfo\Reintegration;

use App\Data\ReintegrationDto;
use App\Models\Reintegration;
use Inertia\Inertia;
use Inertia\Response;

class ListReintegration
{
    public function __invoke(): Response
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
        return Inertia::render('Reintegration/ListModule', [
            'reintegration' => static::handle(request()->search),
        ]);
    }

    public static function handle(?string $term = ''): mixed
    {

        return ReintegrationDto::collect(
            Reintegration::query()->when($term, function ($query, $search) {
                $query->whereAny([

                ], 'LIKE', "%$search%");
            })
        );
    }
}

