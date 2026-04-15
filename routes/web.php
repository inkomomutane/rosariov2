<?php


use App\Http\Controllers\DisciplinaryRecord\ProfileDisciplinaryRecords;
use App\Http\Controllers\DisciplinaryRecord\StoreDisciplinaryRecord;
use App\Http\Controllers\DisciplinaryRecord\UpdateDisciplinaryRecord;
use App\Http\Controllers\Education\DeleteEducation;
use App\Http\Controllers\Education\ListEducation;
use App\Http\Controllers\Education\StoreEducation;
use App\Http\Controllers\Education\UpdateEducation;
use App\Http\Controllers\Experience\DeleteExperience;
use App\Http\Controllers\Experience\ListExperience;
use App\Http\Controllers\Experience\StoreExperience;
use App\Http\Controllers\Experience\UpdateExperience;
use App\Http\Controllers\MemberInfo\Experience\DeleteMemberExperience;
use App\Http\Controllers\MemberInfo\Experience\ListMemberExperience;
use App\Http\Controllers\MemberInfo\Experience\StoreMemberExperience;
use App\Http\Controllers\MemberInfo\Experience\UpdateMemberExperience;
use App\Http\Controllers\MemberInfo\PoliceCard\ShowPoliceCard;
use App\Http\Controllers\MemberInfo\PresenceRegister\CreatePresenceSheet;
use App\Http\Controllers\MemberInfo\PresenceRegister\ExtractPresenceRegister;
use App\Http\Controllers\MemberInfo\PresenceRegister\ListPresenceRegister;
use App\Http\Controllers\MemberInfo\PresenceRegister\MarkPresenceMark;
use App\Http\Controllers\MemberInfo\PresenceRegister\MarkPresenceView;
use App\Http\Controllers\MemberInfo\PresenceRegister\StorePresenceRegister;
use App\Http\Controllers\MemberInfo\Profile\ShowMemberBaseInfo;
use App\Http\Controllers\MemberInfo\Profile\UpdateMemberBaseInfo;
use App\Http\Controllers\MemberInfo\Reintegration\DeleteReintegration;
use App\Http\Controllers\MemberInfo\Reintegration\ListMemberReintegration;
use App\Http\Controllers\MemberInfo\Reintegration\ListReintegration;
use App\Http\Controllers\MemberInfo\Reintegration\StoreMemberReintegration;
use App\Http\Controllers\MemberInfo\Reintegration\StoreReintegration;
use App\Http\Controllers\MemberInfo\Reintegration\UpdateReintegration;
use App\Http\Controllers\Members\DeleteMemberController;
use App\Http\Controllers\Members\ListMembersController;
use App\Http\Controllers\Members\StoreMemberController;
use App\Http\Controllers\Members\UpdateMemberController;
use App\Http\Controllers\Profile\ProfileInfoController;
use App\Http\Controllers\Profile\ProfileInfoUpdateController;
use App\Http\Controllers\Profile\UploadMemberPicture;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
     return redirect()->route('profile.base-info');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile/education', ListEducation::class)->middleware(['auth', 'verified'])->name('profile.education.list');
# store education
Route::post('/profile/education/store', StoreEducation::class)->middleware(['auth', 'verified'])->name('profile.education.store');
# update education
Route::match(['post','match','put'],'/profile/education/{education}/update', UpdateEducation::class)->middleware(['auth', 'verified'])->name('profile.education.update');
# delete education
Route::delete('/profile/education/{education}/delete', DeleteEducation::class)->middleware(['auth', 'verified'])->name('profile.education.delete');


# Experience
Route::get('/profile/experience', ListExperience::class)->middleware(['auth', 'verified'])->name('profile.experience.list');
# store education
Route::post('/profile/experience/store', StoreExperience::class)->middleware(['auth', 'verified'])->name('profile.experience.store');
# update education
Route::match(['post','match','put'],'/profile/experience/{experience}/update', UpdateExperience::class)->middleware(['auth', 'verified'])->name('profile.experience.update');
# delete education
Route::delete('/profile/experience/{experience}/delete', DeleteExperience::class)->middleware(['auth', 'verified'])->name('profile.experience.delete');


Route::get('/profile/base-info', ProfileInfoController::class)->middleware(['auth', 'verified'])->name('profile.base-info');
Route::post('/profile/base-info-update', ProfileInfoUpdateController::class)->middleware(['auth', 'verified'])->name('profile.base-info-update');
Route::get('/profile/view/member/card', function(){
    return Inertia::render('MemberCard',[
        'member' => auth()->user()->getData(),
    ]);
})->name( 'member.card')->middleware(['auth','verified']);

Route::post('member-upload-picture/{member}',UploadMemberPicture::class)->name('member-upload-picture')->middleware(['auth','verified']);

# Members

Route::get('/prm-members', ListMembersController::class)->middleware(['auth', 'verified'])->name('prm-members');
Route::post('/prm-create-member',StoreMemberController::class)->middleware(['auth', 'verified'])->name('prm-create-member');
//UpdateMemberController
Route::post('/prm-update-member/{user}',UpdateMemberController::class)->middleware(['auth', 'verified'])->name('prm-update-member');

Route::delete('prm-delete-member/{member}',DeleteMemberController::class)->middleware(['auth', 'verified'])->name('prm-delete-member');


# Reintegration
Route::get('/profile/reintegration', ListReintegration::class)
    ->middleware(['auth', 'verified'])->name('profile.reintegration.list');

Route::post('/profile/reintegration/store', StoreReintegration::class)
    ->middleware(['auth', 'verified'])->name('profile.reintegration.store');

Route::match(['post','match','put'],'/profile/reintegration/{reintegration}/update', UpdateReintegration::class)
    ->middleware(['auth', 'verified'])->name('profile.reintegration.update');

Route::delete('/profile/reintegration/{reintegration}/delete', DeleteReintegration::class)
    ->middleware(['auth', 'verified'])->name('profile.reintegration.delete');

# DisciplinaryRecord
Route::get('/profile/disciplinary-record', ProfileDisciplinaryRecords::class)->middleware(['auth', 'verified'])->name('profile.disciplinary-record.list');

Route::post('/police/{user}/disciplinary-record/store', StoreDisciplinaryRecord::class)->middleware(['auth', 'verified'])->name('police.disciplinary-record.store');
Route::match(['post','match','put'],'/police/disciplinary-record/{disciplinaryRecord}/update', UpdateDisciplinaryRecord::class)->middleware(['auth', 'verified'])->name('profile.disciplinary-record.update');

# PresenceRegister

Route::get('/members/presence-register/create/{date?}', CreatePresenceSheet::class)->middleware(['auth', 'verified'])->name('members.presence-register.create');
Route::get('/members/presence-register', ListPresenceRegister::class)->middleware(['auth', 'verified'])->name('members.presence-register.list');
Route::post('/member/{register}/presence-register/store', StorePresenceRegister::class)->middleware(['auth', 'verified'])->name('member.presence-register.store');
Route::get('/member/month/presence-extractor',ExtractPresenceRegister::class)->middleware(['auth','verified'])->name('member.month.presence-extractor');



Route::get('/member/edit-base-info/{member}',ShowMemberBaseInfo::class)->middleware(['auth', 'verified'])->name('member.edit-base-info');
Route::post('/member/update-base-info/{member}', UpdateMemberBaseInfo::class)->middleware(['auth', 'verified'])->name('member.update-base-info');

# Member Education
Route::get('/member/{member}/education', \App\Http\Controllers\MemberInfo\Education\ListMemberEducation::class)->middleware(['auth', 'verified'])->name('member.education.list');
Route::post('/member/{member}/education/store', \App\Http\Controllers\MemberInfo\Education\StoreMemberEducation::class)->middleware(['auth', 'verified'])->name('member.education.store');
Route::match(['post','put'],'/member/{member}/education/{education}/update', \App\Http\Controllers\MemberInfo\Education\UpdateMemberEducation::class)->middleware(['auth', 'verified'])->name('member.education.update');
Route::delete('/member/{member}/education/{education}/delete', \App\Http\Controllers\MemberInfo\Education\DeleteMemberEducation::class)->middleware(['auth', 'verified'])->name('member.education.delete');

# Member Experience
Route::get('/member/{member}/experience', ListMemberExperience::class)->middleware(['auth', 'verified'])->name('member.experience.list');
Route::post('/member/{member}/experience/store', StoreMemberExperience::class)->middleware(['auth', 'verified'])->name('member.experience.store');
Route::match(['post','put'],'/member/{member}/experience/{experience}/update', UpdateMemberExperience::class)->middleware(['auth', 'verified'])->name('member.experience.update');
Route::delete('/member/{member}/experience/{experience}/delete', DeleteMemberExperience::class)->middleware(['auth', 'verified'])->name('member.experience.delete');

# Member Reintegration
Route::get('/member/{member}/reintegration', ListMemberReintegration::class)->middleware(['auth', 'verified'])->name('member.reintegration.list');
Route::post('/member/{member}/reintegration/store', StoreMemberReintegration::class)->middleware(['auth', 'verified'])->name('member.reintegration.store');

# Member Disciplinary Records
Route::get('/member/{member}/disciplinary-record', \App\Http\Controllers\MemberInfo\DisciplinaryRecords\ListMemberDisciplinaryRecords::class)->middleware(['auth', 'verified'])->name('member.disciplinary-record.list');
Route::post('/member/{member}/disciplinary-record/store', \App\Http\Controllers\MemberInfo\DisciplinaryRecords\StoreMemberDisciplinaryRecord::class)->middleware(['auth', 'verified'])->name('member.disciplinary-record.store');
Route::match(['post','put'],'/member/{member}/disciplinary-record/{disciplinaryRecord}/update', \App\Http\Controllers\MemberInfo\DisciplinaryRecords\UpdateMemberDisciplinaryRecord::class)->middleware(['auth', 'verified'])->name('member.disciplinary-record.update');
Route::delete('/member/{member}/disciplinary-record/{disciplinaryRecord}/delete', \App\Http\Controllers\MemberInfo\DisciplinaryRecords\DeleteMemberDisciplinaryRecord::class)->middleware(['auth', 'verified'])->name('member.disciplinary-record.delete');

# Member ID Card
Route::get('/member/police-card/{member}/id-card', ShowPoliceCard::class)->middleware(['auth', 'verified'])->name('member.id-card.show');





require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
