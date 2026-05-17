<?php

namespace Modules\Security\Support\Constants;

final class SecurityPermissions
{
    public const VIEW = 'security.view';
    public const CREATE = 'security.create';
    public const UPDATE = 'security.update';
    public const DELETE = 'security.delete';
    public const APPROVE = 'security.approve';
    public const VALIDATE = 'security.validate';
    public const EXPORT = 'security.export';
    public const DIAGNOSTICS = 'security.diagnostics';

    public static function all(): array
    {
        return [
            self::VIEW,
            self::CREATE,
            self::UPDATE,
            self::DELETE,
            self::APPROVE,
            self::VALIDATE,
            self::EXPORT,
            self::DIAGNOSTICS,
        ];
    }
}
