<?php


namespace App\Serializer\Handler;


use App\Entity\Article;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

class ArticleHandler implements SubscribingHandlerInterface
{

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
          [
              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
              'format' => 'json',
              'type' => Article::class,
              'method' => 'serialize'
          ],
          [
              'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
              'format' => 'json',
              'type' => Article::class,
              'method' => 'deserialize'
          ]
        ];
    }

    public function serialize(JsonSerializationVisitor $visitor, Article $article, array $type, Context $context) {
       $date = new \DateTime();

       $data = [
         'title' => $article->getTitle(),
         'content' => $article->getContent(),
         'delivered_at' => $date->format('l jS \of F Y h:i:s A')
       ];

       return $visitor->visitArray($data, $type);
    }

    public function deserialize(JsonSerializationVisitor $visitor, $data) {
        /* Code here... */
    }
}