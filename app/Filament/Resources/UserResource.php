<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\SupervisorActivityLog;
use App\Notifications\UserApproved;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile Information')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Profile Picture')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('avatars')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Upload a profile picture (max 2MB)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->displayFormat('Y-m-d H:i:s'),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->displayFormat('Y-m-d H:i:s')
                            ->helperText('Set automatically when supervisor approves the user'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Security')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText(fn (string $context): string =>
                                $context === 'edit' ? 'Leave blank to keep current password' : 'Set a strong password'
                            ),

                        Forms\Components\Toggle::make('supervisor')
                            ->label('Supervisor')
                            ->helperText('Supervisors can access the Administration section')
                            ->default(false)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(url('/images/default-avatar.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\IconColumn::make('supervisor')
                    ->label('Supervisor')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Verified' : 'Not Verified'),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Approved' : 'Pending'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('resend_verification')
                    ->label('Resend Verification')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Resend Verification Email')
                    ->modalDescription(fn (User $record) => "Send a new verification email to {$record->email}?")
                    ->modalSubmitActionLabel('Yes, send email')
                    ->action(function (User $record) {
                        $record->sendEmailVerificationNotification();

                        Notification::make()
                            ->title('Verification email sent')
                            ->body("Verification email has been sent to {$record->email}")
                            ->success()
                            ->send();

                        // Log supervisor action
                        SupervisorActivityLog::log(
                            action: 'resend_verification',
                            resourceType: 'User',
                            resourceId: $record->id,
                            description: "Resent verification email to: {$record->name} ({$record->email})",
                            metadata: [
                                'user_name' => $record->name,
                                'user_email' => $record->email,
                            ]
                        );
                    })
                    ->visible(fn (User $record) => $record->email_verified_at === null),

                Tables\Actions\Action::make('verify_email')
                    ->label('Verify Email')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Manually Verify Email')
                    ->modalDescription(fn (User $record) => "Manually verify the email for {$record->name}? This will mark their email as verified without requiring them to click a link.")
                    ->modalSubmitActionLabel('Yes, verify email')
                    ->action(function (User $record) {
                        $record->markEmailAsVerified();

                        Notification::make()
                            ->title('Email verified')
                            ->body("{$record->name}'s email has been manually verified")
                            ->success()
                            ->send();

                        // Log supervisor action
                        SupervisorActivityLog::log(
                            action: 'verify_email',
                            resourceType: 'User',
                            resourceId: $record->id,
                            description: "Manually verified email for: {$record->name} ({$record->email})",
                            metadata: [
                                'user_name' => $record->name,
                                'user_email' => $record->email,
                            ]
                        );
                    })
                    ->visible(fn (User $record) => $record->email_verified_at === null),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve User')
                    ->modalDescription(fn (User $record) => "Are you sure you want to approve {$record->name}? They will be able to login and use the platform.")
                    ->modalSubmitActionLabel('Yes, approve')
                    ->action(function (User $record) {
                        $record->update([
                            'approved_at' => now(),
                        ]);

                        // Send notification to the user
                        if (config('mail.enabled', true)) {
                            $record->notify(new UserApproved(auth()->user()));
                        }

                        // Log supervisor action
                        SupervisorActivityLog::log(
                            action: 'approve_user',
                            resourceType: 'User',
                            resourceId: $record->id,
                            description: "Approved user: {$record->name} ({$record->email})",
                            metadata: [
                                'user_name' => $record->name,
                                'user_email' => $record->email,
                            ]
                        );
                    })
                    ->visible(fn (User $record) => $record->email_verified_at !== null && $record->approved_at === null)
                    ->successNotificationTitle('User approved successfully'),

                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        // Send deletion notification email to user
                        if (config('mail.enabled', true)) {
                            $record->notify(new \App\Notifications\AccountDeleted(auth()->user()));
                        }

                        // Log supervisor action before deletion
                        SupervisorActivityLog::log(
                            action: 'delete_user',
                            resourceType: 'User',
                            resourceId: $record->id,
                            description: "Deleted user: {$record->name} ({$record->email})",
                            metadata: [
                                'user_id' => $record->id,
                                'user_name' => $record->name,
                                'user_email' => $record->email,
                                'email_verified_at' => $record->email_verified_at?->toDateTimeString(),
                                'approved_at' => $record->approved_at?->toDateTimeString(),
                                'supervisor' => $record->supervisor,
                            ]
                        );
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $count = $records->count();
                            $users = $records->map(fn ($user) => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                            ])->toArray();

                            // Send deletion notification emails to users
                            if (config('mail.enabled', true)) {
                                foreach ($records as $record) {
                                    $record->notify(new \App\Notifications\AccountDeleted(auth()->user()));
                                }
                            }

                            // Log bulk delete action
                            SupervisorActivityLog::log(
                                action: 'bulk_delete_users',
                                resourceType: 'User',
                                resourceId: null,
                                description: "Bulk deleted {$count} user(s)",
                                metadata: [
                                    'count' => $count,
                                    'users' => $users,
                                ]
                            );

                            // Perform the deletion
                            $records->each->delete();

                            Notification::make()
                                ->title('Users deleted')
                                ->success()
                                ->body("{$count} user(s) have been deleted.")
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
