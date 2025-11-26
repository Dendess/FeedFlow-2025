
```
FeedFlow-2025
├─ .dockerignore
├─ .editorconfig
├─ app
│  ├─ Actions
│  │  ├─ Organization
│  │  │  ├─ DeleteOrganizationAction.php
│  │  │  ├─ StoreOrganizationAction.php
│  │  │  └─ UpdateOrganizationAction.php
│  │  ├─ Survey
│  │  │  ├─ CloseSurveyAction.php
│  │  │  ├─ StoreSurveyAction.php
│  │  │  ├─ StoreSurveyAnswerAction.php
│  │  │  ├─ StoreSurveyQuestionAction.php
│  │  │  └─ UpdateSurveyAction.php
│  │  └─ User
│  │     ├─ DeleteUserAction.php
│  │     ├─ StoreUserAction.php
│  │     └─ UpdateUserAction.php
│  ├─ Console
│  │  └─ Commands
│  │     ├─ CheckForSurveyToClose.php
│  │     └─ SendSurveyDailyReports.php
│  ├─ DTOs
│  │  ├─ OrganizationDTO.php
│  │  ├─ SurveyAnswerDTO.php
│  │  ├─ SurveyDTO.php
│  │  ├─ SurveyQuestionDTO.php
│  │  └─ UserDTO.php
│  ├─ Events
│  │  ├─ DailyAnswersThresholdReached.php
│  │  ├─ SurveyAnswerSubmitted.php
│  │  └─ SurveyClosed.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Auth
│  │  │  │  ├─ AuthenticatedSessionController.php
│  │  │  │  ├─ ConfirmablePasswordController.php
│  │  │  │  ├─ EmailVerificationNotificationController.php
│  │  │  │  ├─ EmailVerificationPromptController.php
│  │  │  │  ├─ NewPasswordController.php
│  │  │  │  ├─ PasswordController.php
│  │  │  │  ├─ PasswordResetLinkController.php
│  │  │  │  ├─ RegisteredUserController.php
│  │  │  │  └─ VerifyEmailController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ OrganizationController.php
│  │  │  ├─ ProfileController.php
│  │  │  └─ SurveyController.php
│  │  └─ Requests
│  │     ├─ Auth
│  │     │  └─ LoginRequest.php
│  │     ├─ Organization
│  │     │  ├─ DeleteOrganization.php
│  │     │  ├─ StoreOrganization.php
│  │     │  └─ UpdateOrganization.php
│  │     ├─ ProfileUpdateRequest.php
│  │     ├─ Survey
│  │     │  ├─ DeleteSurveyRequest.php
│  │     │  ├─ StoreSurveyAnswerRequest.php
│  │     │  ├─ StoreSurveyQuestionRequest.php
│  │     │  ├─ StoreSurveyRequest.php
│  │     │  └─ UpdateSurveyRequest.php
│  │     └─ User
│  │        ├─ DeleteUserRequest.php
│  │        ├─ StoreUserRequest.php
│  │        └─ UpdateUserRequest.php
│  ├─ Listeners
│  │  ├─ SendDailyReport.php
│  │  ├─ SendFinalReportOnClose.php
│  │  └─ SendNewAnswerNotification.php
│  ├─ Models
│  │  ├─ Organization.php
│  │  ├─ OrganizationUser.php
│  │  ├─ Survey.php
│  │  ├─ SurveyAnswer.php
│  │  ├─ SurveyQuestion.php
│  │  └─ User.php
│  ├─ Policies
│  │  ├─ OrganizationPolicy.php
│  │  └─ SurveyPolicy.php
│  ├─ Providers
│  │  └─ AppServiceProvider.php
│  └─ View
│     └─ Components
│        ├─ AppLayout.php
│        └─ GuestLayout.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2025_11_22_120129_create_organizations_table.php
│  │  ├─ 2025_11_22_120139_create_organization_user_table.php
│  │  ├─ 2025_11_22_120149_create_surveys_table.php
│  │  ├─ 2025_11_22_120200_create_survey_questions_table.php
│  │  ├─ 2025_11_22_120219_create_survey_answers_table.php
│  │  └─ 2025_11_25_100105_add_token_to_surveys_table.php
│  └─ seeders
│     └─ DatabaseSeeder.php
├─ docker
│  ├─ Dockerfile
│  └─ nginx
│     └─ default.conf
├─ docker-compose.yml
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ postcss.config.js
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ auth
│     │  ├─ confirm-password.blade.php
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ components
│     │  ├─ application-logo.blade.php
│     │  ├─ auth-session-status.blade.php
│     │  ├─ danger-button.blade.php
│     │  ├─ dropdown-link.blade.php
│     │  ├─ dropdown.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ modal.blade.php
│     │  ├─ nav-link.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ responsive-nav-link.blade.php
│     │  ├─ secondary-button.blade.php
│     │  └─ text-input.blade.php
│     ├─ dashboard.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ guest.blade.php
│     │  └─ navigation.blade.php
│     ├─ profile
│     │  ├─ edit.blade.php
│     │  └─ partials
│     │     ├─ delete-user-form.blade.php
│     │     ├─ update-password-form.blade.php
│     │     └─ update-profile-information-form.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ auth.php
│  ├─ console.php
│  └─ web.php
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  ├─ Auth
│  │  │  ├─ AuthenticationTest.php
│  │  │  ├─ EmailVerificationTest.php
│  │  │  ├─ PasswordConfirmationTest.php
│  │  │  ├─ PasswordResetTest.php
│  │  │  ├─ PasswordUpdateTest.php
│  │  │  └─ RegistrationTest.php
│  │  ├─ ExampleTest.php
│  │  └─ ProfileTest.php
│  ├─ TestCase.php
│  └─ Unit
│     ├─ ExampleTest.php
│     ├─ StoreSurveyActionTest.php
│     └─ StoreSurveyAnswerActionTest.php
└─ webpack.mix.js

```
```
FeedFlow-2025
├─ .dockerignore
├─ .editorconfig
├─ app
│  ├─ Actions
│  │  ├─ Organization
│  │  │  ├─ DeleteOrganizationAction.php
│  │  │  ├─ StoreOrganizationAction.php
│  │  │  └─ UpdateOrganizationAction.php
│  │  ├─ Survey
│  │  │  ├─ CloseSurveyAction.php
│  │  │  ├─ StoreSurveyAction.php
│  │  │  ├─ StoreSurveyAnswerAction.php
│  │  │  ├─ StoreSurveyQuestionAction.php
│  │  │  └─ UpdateSurveyAction.php
│  │  └─ User
│  │     ├─ DeleteUserAction.php
│  │     ├─ StoreUserAction.php
│  │     └─ UpdateUserAction.php
│  ├─ Console
│  │  └─ Commands
│  │     ├─ CheckForSurveyToClose.php
│  │     └─ SendSurveyDailyReports.php
│  ├─ DTOs
│  │  ├─ OrganizationDTO.php
│  │  ├─ SurveyAnswerDTO.php
│  │  ├─ SurveyDTO.php
│  │  ├─ SurveyQuestionDTO.php
│  │  └─ UserDTO.php
│  ├─ Events
│  │  ├─ DailyAnswersThresholdReached.php
│  │  ├─ SurveyAnswerSubmitted.php
│  │  └─ SurveyClosed.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Auth
│  │  │  │  ├─ AuthenticatedSessionController.php
│  │  │  │  ├─ ConfirmablePasswordController.php
│  │  │  │  ├─ EmailVerificationNotificationController.php
│  │  │  │  ├─ EmailVerificationPromptController.php
│  │  │  │  ├─ NewPasswordController.php
│  │  │  │  ├─ PasswordController.php
│  │  │  │  ├─ PasswordResetLinkController.php
│  │  │  │  ├─ RegisteredUserController.php
│  │  │  │  └─ VerifyEmailController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ OrganizationController.php
│  │  │  ├─ ProfileController.php
│  │  │  └─ SurveyController.php
│  │  └─ Requests
│  │     ├─ Auth
│  │     │  └─ LoginRequest.php
│  │     ├─ Organization
│  │     │  ├─ DeleteOrganization.php
│  │     │  ├─ StoreOrganization.php
│  │     │  └─ UpdateOrganization.php
│  │     ├─ ProfileUpdateRequest.php
│  │     ├─ Survey
│  │     │  ├─ DeleteSurveyRequest.php
│  │     │  ├─ StoreSurveyAnswerRequest.php
│  │     │  ├─ StoreSurveyQuestionRequest.php
│  │     │  ├─ StoreSurveyRequest.php
│  │     │  └─ UpdateSurveyRequest.php
│  │     └─ User
│  │        ├─ DeleteUserRequest.php
│  │        ├─ StoreUserRequest.php
│  │        └─ UpdateUserRequest.php
│  ├─ Listeners
│  │  ├─ SendDailyReport.php
│  │  ├─ SendFinalReportOnClose.php
│  │  └─ SendNewAnswerNotification.php
│  ├─ Models
│  │  ├─ Organization.php
│  │  ├─ OrganizationUser.php
│  │  ├─ Survey.php
│  │  ├─ SurveyAnswer.php
│  │  ├─ SurveyQuestion.php
│  │  └─ User.php
│  ├─ Policies
│  │  ├─ OrganizationPolicy.php
│  │  └─ SurveyPolicy.php
│  ├─ Providers
│  │  └─ AppServiceProvider.php
│  └─ View
│     └─ Components
│        ├─ AppLayout.php
│        └─ GuestLayout.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2025_11_22_120129_create_organizations_table.php
│  │  ├─ 2025_11_22_120139_create_organization_user_table.php
│  │  ├─ 2025_11_22_120149_create_surveys_table.php
│  │  ├─ 2025_11_22_120200_create_survey_questions_table.php
│  │  ├─ 2025_11_22_120219_create_survey_answers_table.php
│  │  └─ 2025_11_25_100105_add_token_to_surveys_table.php
│  └─ seeders
│     └─ DatabaseSeeder.php
├─ docker
│  ├─ Dockerfile
│  └─ nginx
│     └─ default.conf
├─ docker-compose.yml
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ postcss.config.js
├─ README.md
├─ README_PROJECT_TREE.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ auth
│     │  ├─ confirm-password.blade.php
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ components
│     │  ├─ application-logo.blade.php
│     │  ├─ auth-session-status.blade.php
│     │  ├─ danger-button.blade.php
│     │  ├─ dropdown-link.blade.php
│     │  ├─ dropdown.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ modal.blade.php
│     │  ├─ nav-link.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ responsive-nav-link.blade.php
│     │  ├─ secondary-button.blade.php
│     │  └─ text-input.blade.php
│     ├─ dashboard.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ guest.blade.php
│     │  └─ navigation.blade.php
│     ├─ profile
│     │  ├─ edit.blade.php
│     │  └─ partials
│     │     ├─ delete-user-form.blade.php
│     │     ├─ update-password-form.blade.php
│     │     └─ update-profile-information-form.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ auth.php
│  ├─ console.php
│  └─ web.php
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  ├─ Auth
│  │  │  ├─ AuthenticationTest.php
│  │  │  ├─ EmailVerificationTest.php
│  │  │  ├─ PasswordConfirmationTest.php
│  │  │  ├─ PasswordResetTest.php
│  │  │  ├─ PasswordUpdateTest.php
│  │  │  └─ RegistrationTest.php
│  │  ├─ ExampleTest.php
│  │  └─ ProfileTest.php
│  ├─ TestCase.php
│  └─ Unit
│     ├─ ExampleTest.php
│     ├─ StoreSurveyActionTest.php
│     └─ StoreSurveyAnswerActionTest.php
└─ webpack.mix.js

```
```
FeedFlow-2025
├─ .dockerignore
├─ .editorconfig
├─ app
│  ├─ Actions
│  │  ├─ Organization
│  │  │  ├─ DeleteOrganizationAction.php
│  │  │  ├─ StoreOrganizationAction.php
│  │  │  └─ UpdateOrganizationAction.php
│  │  ├─ Survey
│  │  │  ├─ CloseSurveyAction.php
│  │  │  ├─ StoreSurveyAction.php
│  │  │  ├─ StoreSurveyAnswerAction.php
│  │  │  ├─ StoreSurveyQuestionAction.php
│  │  │  └─ UpdateSurveyAction.php
│  │  └─ User
│  │     ├─ DeleteUserAction.php
│  │     ├─ StoreUserAction.php
│  │     └─ UpdateUserAction.php
│  ├─ Console
│  │  └─ Commands
│  │     ├─ CheckForSurveyToClose.php
│  │     └─ SendSurveyDailyReports.php
│  ├─ DTOs
│  │  ├─ OrganizationDTO.php
│  │  ├─ SurveyAnswerDTO.php
│  │  ├─ SurveyDTO.php
│  │  ├─ SurveyQuestionDTO.php
│  │  └─ UserDTO.php
│  ├─ Events
│  │  ├─ DailyAnswersThresholdReached.php
│  │  ├─ SurveyAnswerSubmitted.php
│  │  └─ SurveyClosed.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Auth
│  │  │  │  ├─ AuthenticatedSessionController.php
│  │  │  │  ├─ ConfirmablePasswordController.php
│  │  │  │  ├─ EmailVerificationNotificationController.php
│  │  │  │  ├─ EmailVerificationPromptController.php
│  │  │  │  ├─ NewPasswordController.php
│  │  │  │  ├─ PasswordController.php
│  │  │  │  ├─ PasswordResetLinkController.php
│  │  │  │  ├─ RegisteredUserController.php
│  │  │  │  └─ VerifyEmailController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ OrganizationController.php
│  │  │  ├─ ProfileController.php
│  │  │  └─ SurveyController.php
│  │  └─ Requests
│  │     ├─ Auth
│  │     │  └─ LoginRequest.php
│  │     ├─ Organization
│  │     │  ├─ DeleteOrganization.php
│  │     │  ├─ StoreOrganization.php
│  │     │  ├─ StoreOrganizationRequest.php
│  │     │  ├─ UpdateOrganization.php
│  │     │  └─ UpdateOrganizationRequest.php
│  │     ├─ ProfileUpdateRequest.php
│  │     ├─ Survey
│  │     │  ├─ DeleteSurveyRequest.php
│  │     │  ├─ StoreSurveyAnswerRequest.php
│  │     │  ├─ StoreSurveyQuestionRequest.php
│  │     │  ├─ StoreSurveyRequest.php
│  │     │  └─ UpdateSurveyRequest.php
│  │     └─ User
│  │        ├─ DeleteUserRequest.php
│  │        ├─ StoreUserRequest.php
│  │        └─ UpdateUserRequest.php
│  ├─ Listeners
│  │  ├─ SendDailyReport.php
│  │  ├─ SendFinalReportOnClose.php
│  │  └─ SendNewAnswerNotification.php
│  ├─ Models
│  │  ├─ Organization.php
│  │  ├─ OrganizationUser.php
│  │  ├─ Survey.php
│  │  ├─ SurveyAnswer.php
│  │  ├─ SurveyQuestion.php
│  │  └─ User.php
│  ├─ Policies
│  │  ├─ OrganizationPolicy.php
│  │  └─ SurveyPolicy.php
│  ├─ Providers
│  │  └─ AppServiceProvider.php
│  └─ View
│     └─ Components
│        ├─ AppLayout.php
│        └─ GuestLayout.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2025_11_22_120129_create_organizations_table.php
│  │  ├─ 2025_11_22_120139_create_organization_user_table.php
│  │  ├─ 2025_11_22_120149_create_surveys_table.php
│  │  ├─ 2025_11_22_120200_create_survey_questions_table.php
│  │  ├─ 2025_11_22_120219_create_survey_answers_table.php
│  │  └─ 2025_11_25_100105_add_token_to_surveys_table.php
│  └─ seeders
│     └─ DatabaseSeeder.php
├─ docker
│  ├─ Dockerfile
│  └─ nginx
│     └─ default.conf
├─ docker-compose.yml
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ postcss.config.js
├─ README.md
├─ README_PROJECT_TREE.md
├─ resources
│  ├─ css
│  │  ├─ app.css
│  │  └─ organizations.css
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ auth
│     │  ├─ confirm-password.blade.php
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ components
│     │  ├─ application-logo.blade.php
│     │  ├─ auth-session-status.blade.php
│     │  ├─ danger-button.blade.php
│     │  ├─ dropdown-link.blade.php
│     │  ├─ dropdown.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ modal.blade.php
│     │  ├─ nav-link.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ responsive-nav-link.blade.php
│     │  ├─ secondary-button.blade.php
│     │  └─ text-input.blade.php
│     ├─ dashboard.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ guest.blade.php
│     │  └─ navigation.blade.php
│     ├─ organizations
│     │  ├─ create.blade.php
│     │  ├─ edit.blade.php
│     │  ├─ index.blade.php
│     │  ├─ show.blade.php
│     │  └─ _form.blade.php
│     ├─ profile
│     │  ├─ edit.blade.php
│     │  └─ partials
│     │     ├─ delete-user-form.blade.php
│     │     ├─ update-password-form.blade.php
│     │     └─ update-profile-information-form.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ auth.php
│  ├─ console.php
│  └─ web.php
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  ├─ Auth
│  │  │  ├─ AuthenticationTest.php
│  │  │  ├─ EmailVerificationTest.php
│  │  │  ├─ PasswordConfirmationTest.php
│  │  │  ├─ PasswordResetTest.php
│  │  │  ├─ PasswordUpdateTest.php
│  │  │  └─ RegistrationTest.php
│  │  ├─ ExampleTest.php
│  │  └─ ProfileTest.php
│  ├─ TestCase.php
│  └─ Unit
│     ├─ ExampleTest.php
│     ├─ StoreSurveyActionTest.php
│     └─ StoreSurveyAnswerActionTest.php
└─ webpack.mix.js

```