import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
export const product = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: product.url(options),
    method: 'get',
})

product.definition = {
    methods: ["get","head"],
    url: '/zeropay-product',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
product.url = (options?: RouteQueryOptions) => {
    return product.definition.url + queryParams(options)
}

/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
product.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: product.url(options),
    method: 'get',
})

/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
product.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: product.url(options),
    method: 'head',
})

/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
const productForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: product.url(options),
    method: 'get',
})

/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
productForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: product.url(options),
    method: 'get',
})

/**
* @see routes/web.php:226
* @route '/zeropay-product'
*/
productForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: product.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

product.form = productForm

const zeropay = {
    product: Object.assign(product, product),
}

export default zeropay