export type AlertDto = {
    type: AlertStatus;
    message: string;
};
export type AlertStatus = 'success' | 'error' | 'info' | 'warning';
export type CaseType = 'ray_x' | 'ecography' | 'tac' | 'mri' | 'laboratory' | 'other';
export type ColumnOrderDto = {
    order: number;
    field_name: string;
    showing: boolean;
};
export type FileData = {
    name: string;
    size: string;
    type: string;
    url: string;
    id: string | null;
    uuid: string | null;
    collection_name: string | null;
    disk: string | null;
};
export type FormUIMap = 'users' | 'doctors' | 'patients' | 'cases' | 'case_reviews' | 'invoices' | 'payments';
export type FullPatientCaseDto = {
    case_code: string | null;
    person_id: string | null;
    patient_name: string | null;
    patient_last_name: string | null;
    patient_verified: boolean | null;
    priority: Urgency | null;
    title: string | null;
    description: string | null;
    case_type: CaseType | null;
    first_review: string | null;
    final_review: string | null;
    case_date: any | string | null;
    first_review_date: any | string | null;
    last_review_date: any | string | null;
    requester_doctor_name: string | null;
    status: PatientCaseStatus | null;
    assigned_doctor_name: string | null;
    last_reviewer_doctor_name: string | null;
    id: string | null;
    attachments: Array<any> | null;
};
export type InvoiceStatus = 'draft' | 'issued' | 'partially_paid' | 'paid' | 'refunded' | 'overdue' | 'canceled';
export type KeyValueDto = {
    key: string;
    value: string;
};
export type MaritalStatus = 'single' | 'married' | 'divorced' | 'widowed';
export type PatientCaseRequestDto = {
    priority: Urgency;
    title: string;
    description: string;
    status: PatientCaseStatus | null;
    case_type: CaseType | null;
    attachments: Array<any> | null;
    uploaded_attachments: Array<any> | null;
    id: string | null;
    case_code: string | null;
};
export type PatientCaseStatus =
    | 'submitted'
    | 'awaiting_assignment'
    | 'assigned'
    | 'under_review'
    | 'reviewed'
    | 'in_secondary_review'
    | 'secondary_reviewed'
    | 'closed'
    | 'reopened'
    | 'cancelled';
export type PersonDto = {
    name: string;
    last_name: string;
    gender: Sex | null;
    marital_status: MaritalStatus | null;
    middle_name: string | null;
    birth_date: any | string | null;
    nationality: string | null;
    province: string | null;
    city: string | null;
    postal_code: string | null;
    address_one: string | null;
    address_two: string | null;
    address_three: string | null;
    address_four: string | null;
    phone_one: string | null;
    phone_two: string | null;
    phone_three: string | null;
    phone_four: string | null;
    death_date: any | string | null;
    cause_of_death: string | null;
};
export type ProfileDto = {
    id: string | null;
    name: string | null;
    email: string | null;
    email_verified_at: string | null;
    full_name: string;
    birth_date: string | null;
    gender: Sex | null;
    marital_status: MaritalStatus | null;
    nationality: string | null;
    province: string | null;
    city: string | null;
    postal_code: string | null;
    address_one: string | null;
    address_two: string | null;
    address_three: string | null;
    address_four: string | null;
    phone_one: string | null;
    phone_two: string | null;
    phone_three: string | null;
    phone_four: string | null;
    death_date: string | null;
    cause_of_death: string | null;
    user_id: string | null;
    registration_date: string | null;
    is_doctor: boolean;
    is_patient: boolean;
    verified: boolean;
    verified_date: string | null;
    verified_using: string | null;
    verification_code: string | null;
    verification_document: string | null;
    hospital: string | null;
    specialization: string | null;
    license_number: string | null;
    license_issue_date: string | null;
    license_expiry_date: string | null;
    years_of_experience: string | null;
    medical_school: string | null;
    graduation_date: string | null;
};
export type ReviewStatus = 'pending' | 'in_progress' | 'completed';
export type Sex = 'male' | 'female' | 'other';
export type Urgency = 'low' | 'normal' | 'emergency' | 'critical';
export type UserDto = {
    name: string;
    email: string;
    password: string | null;
};
