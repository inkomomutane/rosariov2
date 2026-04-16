export type AlertStatus = 'success' | 'error' | 'info' | 'warning';
export type CaseType = 'ray_x' | 'ecography' | 'tac' | 'mri' | 'laboratory' | 'other';
export type FormUIMap = 'users' | 'doctors' | 'patients' | 'cases' | 'case_reviews' | 'invoices' | 'payments';
export type InvoiceStatus = 'draft' | 'issued' | 'partially_paid' | 'paid' | 'refunded' | 'overdue' | 'canceled';
export type MaritalStatus = 'single' | 'married' | 'divorced' | 'widowed';
export type PatientCaseStatus = 'submitted' | 'awaiting_assignment' | 'assigned' | 'under_review' | 'reviewed' | 'in_secondary_review' | 'secondary_reviewed' | 'closed' | 'reopened' | 'cancelled';
export type ReviewStatus = 'pending' | 'in_progress' | 'completed';
export type Sex = 'male' | 'female' | 'other';
export type Urgency = 'low' | 'normal' | 'emergency' | 'critical';
export type UserDto = {
name: string;
email: string;
password: string | null;
};
