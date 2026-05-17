import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
const EditJob = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJob.url(args, options),
    method: 'get',
})

EditJob.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
    url: '/titansolo/jobs/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
    url: '/titanpro/jobs/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
EditJob.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return EditJob.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
EditJob.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJob.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
EditJob.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJob.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
const EditJobForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJob.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
EditJobForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJob.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/EditJob.php:7
* @route '/titansolo/jobs/{record}/edit'
========
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/titanpro/jobs/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/EditJob.ts
*/
EditJobForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJob.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJob.form = EditJobForm

export default EditJob