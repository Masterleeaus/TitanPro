import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
const DispatchBoard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DispatchBoard.url(options),
    method: 'get',
})

DispatchBoard.definition = {
    methods: ["get","head"],
    url: '/groundzero/dispatch-board',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
DispatchBoard.url = (options?: RouteQueryOptions) => {
    return DispatchBoard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
DispatchBoard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DispatchBoard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
DispatchBoard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: DispatchBoard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
const DispatchBoardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: DispatchBoard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
DispatchBoardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: DispatchBoard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
DispatchBoardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: DispatchBoard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

DispatchBoard.form = DispatchBoardForm

export default DispatchBoard