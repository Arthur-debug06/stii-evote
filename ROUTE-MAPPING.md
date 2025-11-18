# Laravel to PHP Route Mapping

## Authentication Routes

### Login
- **Laravel Route**: `GET /` → `LoginController@login`
- **Laravel Route**: `POST /authenticate` → `LoginController@authenticate`
- **Laravel Route**: `GET /logout` → `LoginController@logout`
- **PHP Equivalent**: Already exists in `nonlaravel/controllers/Controllers/LoginController.php`
- **Status**: ✅ Exists

### Register
- **Laravel Route**: `GET /register` → `RegisterController@index`
- **Laravel Route**: `POST /register` → `RegisterController@store`
- **PHP Location**: `nonlaravel/controllers/RegisterController.php` (to be created)
- **Status**: ⏳ Needs migration

### Forgot Password
- **Laravel Route**: `GET /forgot-password` → `ForgotPasswordController@index`
- **Laravel Route**: `POST /forgot-password` → `ForgotPasswordController@sendOtp`
- **PHP Location**: `nonlaravel/controllers/ForgotPasswordController.php` (to be created)
- **Status**: ⏳ Needs migration

### OTP
- **Laravel Route**: `GET /otp` → `OtpController@index`
- **Laravel Route**: `POST /otp` → `OtpController@verifyOtp`
- **Laravel Route**: `POST /otp/resend` → `OtpController@resendOtp`
- **PHP Location**: `nonlaravel/controllers/OtpController.php` (to be created)
- **Status**: ⏳ Needs migration

## Dashboard
- **Laravel Route**: `GET /dashboard` → Returns view directly
- **PHP Location**: `nonlaravel/controllers/Controllers/DashboardController.php`
- **Status**: ✅ Exists

## Appointment Routes
- **Laravel Route**: `GET /appointment` → `AppointmentController@index`
- **Laravel Route**: `POST /appointment` → `AppointmentController@store`
- **PHP Location**: `nonlaravel/controllers/AppointmentController.php` (to be created)
- **Status**: ⏳ Needs migration

## PDF Generation Routes
- **Laravel Route**: `GET /pdf/candidates-list` → `PdfController@candidatesList`
- **Laravel Route**: `GET /pdf/candidates-election` → `PdfController@candidatesElection`
- **Laravel Route**: `GET /pdf/students-account` → `PdfController@studentsAccount`
- **Laravel Route**: `GET /pdf/admin-account` → `PdfController@adminAccount`
- **Laravel Route**: `GET /pdf/student-voters/{id}` → `PdfController@studentVoters`
- **PHP Location**: `nonlaravel/controllers/PdfController.php` (to be created)
- **Status**: ⏳ Needs migration

## Student Voter Routes
- **Laravel Route**: `GET /student-voter` → `PdfController@studentVotersIndex`
- **Laravel Route**: `GET /api/student-voters/{id}` → `PdfController@studentVotersJson`
- **PHP Location**: Include in `PdfController.php`
- **Status**: ⏳ Needs migration

## Notification Routes
- **Laravel Route**: `POST /notifications/{notification}/mark-read` → Closure
- **Laravel Route**: `GET /api/notifications/unread` → Closure
- **PHP Location**: `nonlaravel/controllers/NotificationController.php` (to be created)
- **Status**: ⏳ Needs migration

## Voting Routes
- **Laravel Route**: `POST /api/voting/process-expired` → Closure (uses VotingStatusService)
- **Laravel Route**: `GET /voting-histories` → `VotingHistoryController@index`
- **Laravel Route**: `GET /voting-histories/{id}` → `VotingHistoryController@show`
- **PHP Location**: `nonlaravel/controllers/VotingController.php` (to be created)
- **Status**: ⏳ Needs migration

## Attachment Routes
- **Laravel Route**: `GET /attachments/candidacy-grade/{id}` → `AttachmentController@candidacyGrade`
- **Laravel Route**: `GET /attachments/student-image/{student}/{type}` → `AttachmentController@studentImage`
- **PHP Location**: `nonlaravel/controllers/AttachmentController.php` (to be created)
- **Status**: ⏳ Needs migration

## Public File Routes
- **Laravel Route**: `GET /files/{path}` → `PublicFileController@show`
- **PHP Location**: `nonlaravel/controllers/PublicFileController.php` (to be created)
- **Status**: ⏳ Needs migration

## Candidacy Management
- **Laravel Route**: `GET /candidacy-management` → Returns view directly
- **Laravel Route**: `GET /candidacy-management1` → Returns view directly
- **PHP Location**: Include in `CandidacyController.php` (to be created)
- **Status**: ⏳ Needs migration

## Partylist Routes
- **Laravel Route**: `GET /partylist/{id}` → `PartylistController@show`
- **PHP Location**: `nonlaravel/controllers/PartylistController.php` (to be created)
- **Status**: ⏳ Needs migration

## STSG Admin Notifications
- **Laravel Route**: `GET /stsg-admin-notifications` → Returns view directly
- **PHP Location**: Include in `NotificationController.php`
- **Status**: ⏳ Needs migration

## Livewire Components (Need conversion to AJAX)
These are handled by Livewire and need to be converted to traditional PHP controllers with AJAX endpoints:

1. **Dashboard** - `app/Livewire/Dashboard/Dashboard.php`
2. **Feedback** - `app/Livewire/Feedback/Feedback.php`
3. **VotingExclusive** - `app/Livewire/VotingExclusive/VotingExclusive.php`
4. **StudentVoter** - `app/Livewire/StudentVoter/StudentVoter.php`
5. **SystemSettings** - `app/Livewire/SystemSettings/SystemSettings.php`
6. **StudentAccount** - `app/Livewire/StudentAccount/StudentAccount.php`
7. **STSGAdminNotification** - `app/Livewire/STSGAdminNotification/STSGAdminNotification.php`
8. **SetSignatory** - `app/Livewire/SetSignatory/SetSignatory.php`
9. **RoomToRoom** - `app/Livewire/RoomToRoom/RoomToRoom.php`
10. **RegistrationRequest** - `app/Livewire/RegistrationRequest/RegistrationRequest.php`
11. **RegistrationApproved** - `app/Livewire/RegistrationApproved/RegistrationApproved.php`
12. **RegistrationRejected** - `app/Livewire/RegistrationRejected/RegistrationRejected.php`
13. **ProfileManagement** - `app/Livewire/ProfileManagement/ProfileManagement.php`
14. **Position** - `app/Livewire/Position/PositionManagement.php`
15. **PartylistManagement** - `app/Livewire/PartylistManagement/PartylistManagement.php`
16. **DepartmentManagement** - `app/Livewire/DepartmentManagement/DepartmentManagement.php`
17. **CourseManagement** - `app/Livewire/CourseManagement/CourseManagement.php`
18. And more...

## Catch-all Route
- **Laravel Route**: `GET {any}` → `RouteController@routes`
- **Note**: This dynamically resolves views based on URL path
- **PHP Location**: Will be handled by `nonlaravel/bootstrap.php` routing logic
- **Status**: ⏳ Needs implementation in router

## Middleware Requirements
All authenticated routes need to check session/auth state in PHP equivalent.
