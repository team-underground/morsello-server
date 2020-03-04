<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Bookmark
{
    public function allBookmarks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        return Bit::with('bookmarks')->whereHas('bookmarks', function ($query) {
            $query->where('user_id', auth()->id());
        });
        // return DB::table('bits')
        //     ->join('bookmarks', 'bits.id', '=', 'bookmarks.bit_id')
        //     ->where('bookmarks.user_id', auth()->id());
    }
}
