<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A user/profile summary card for dashboard headers or sidebars.
 */
class UserProfileCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('user-profile-card')
            ->label(__('User / Profile Card'))
            ->icon('heroicon-o-user-circle')
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->placeholder(__('Jane Smith'))
                    ->required(),

                TextInput::make('role')
                    ->label(__('Role / Position'))
                    ->placeholder(__('Field Operations Manager')),

                TextInput::make('avatar_url')
                    ->label(__('Avatar URL (optional)'))
                    ->placeholder(__('https://example.com/avatar.jpg')),

                Textarea::make('description')
                    ->label(__('Bio / Description'))
                    ->placeholder(__('Short bio or status message'))
                    ->rows(2),

                TextInput::make('email')
                    ->label(__('Email (optional)'))
                    ->email()
                    ->placeholder(__('jane@example.com')),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $name        = e($data['name'] ?? '');
        $role        = e($data['role'] ?? '');
        $avatarUrl   = e($data['avatar_url'] ?? '');
        $description = e($data['description'] ?? '');
        $email       = e($data['email'] ?? '');

        $initials = $this->getInitials($name);

        $avatarHtml = $avatarUrl !== ''
            ? '<img src="' . $avatarUrl . '" alt="' . $name . '" class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" loading="lazy" />'
            : '<div class="h-16 w-16 rounded-full bg-primary-600 flex items-center justify-center text-white text-xl font-bold border-2 border-primary-700">' . $initials . '</div>';

        $roleHtml = $role !== ''
            ? '<p class="text-sm text-gray-500 dark:text-gray-400">' . $role . '</p>'
            : '';

        $descHtml = $description !== ''
            ? '<p class="mt-3 text-sm text-gray-600 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-3">' . $description . '</p>'
            : '';

        $emailHtml = $email !== ''
            ? '<p class="mt-1 text-xs text-gray-400 dark:text-gray-500">' . $email . '</p>'
            : '';

        return <<<HTML
<div class="fi-profile-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <div class="flex items-center gap-4">
        {$avatarHtml}
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{$name}</h3>
            {$roleHtml}
            {$emailHtml}
        </div>
    </div>
    {$descHtml}
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }

    private function getInitials(string $name): string
    {
        $parts = array_filter(explode(' ', trim($name)));
        if (empty($parts)) {
            return '?';
        }

        $initials = strtoupper(substr($parts[0], 0, 1));

        if (count($parts) > 1) {
            $initials .= strtoupper(substr(end($parts), 0, 1));
        }

        return $initials;
    }
}
