<?php

namespace App\Contracts;

/**
 * Marker interface for Eloquent models that must be automatically scoped
 * to the current tenant's organisation.
 *
 * Implement this interface together with the `BelongsToTenant` trait so
 * that every query on the model automatically receives a
 * `WHERE organization_id = ?` clause matching the authenticated user's
 * organisation.
 */
interface TenantAware {}
