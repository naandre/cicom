<?php

namespace App\GraphQL\Queries;

use App\Models\Articles\Article;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleFilter
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /**@var Article $rticle*/
        $article=Article::orderBy('title','ASC');
        if(isset($args['title'])) $article=$article->where('title','like',"%".$args['title']."%");
        if(isset($args['category_id'])) $article=$article->where('category_id',$args['category_id']);
        if(isset($args['line_id'])) $article=$article->where('line_id',$args['line_id']);
        if(isset($args['publication_date'])) $article=$article->where('publication_date',$args['publication_date']);
        if(isset($args['author'])) $article=$article->select('articles.id as id','articles.title as title', 'articles.description as description','articles.category_id as category_id','articles.line_id as line_id','articles.editorial as editorial','articles.publication_date as publication_date','articles.file as file')
            ->join('authors','articles.id','=','authors.article_id')->where('authors.name','like',"%".$args['author']."%");

        return $article->get();
    }
}
