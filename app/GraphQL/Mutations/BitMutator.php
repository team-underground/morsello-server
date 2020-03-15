<?php

namespace App\GraphQL\Mutations;

use App\Bit;
use App\Category;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BitMutator
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function store($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $bit = new \App\Bit($args);

        $categoryIds = Category::whereIn('name', $args['tags'])->pluck('id')->all();

        $bit = $context->user()->bits()->save($bit);
        $bit->tags()->sync($categoryIds);
        return $bit->load('tags');
    }
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $bit = Bit::findOrFail($args['id']);
        $categoryIds = Category::whereIn('name', $args['tags'])->pluck('id')->all();

        $bit->update([
            'title' => $args['title'],
            'snippet' => $args['snippet']
        ]);
        $bit->tags()->sync($categoryIds);
        return $bit->load('tags');
    }
}
