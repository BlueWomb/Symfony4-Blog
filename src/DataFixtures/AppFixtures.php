<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Author;
use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Tag;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category_1 = new Category();
        $category_1->setName("Categoria_A");
        $category_1->setDescription("Descrizione categoria_A");
        $manager->persist($category_1);

        $category_2 = new Category();
        $category_2->setName("Categoria_B");
        $category_2->setDescription("Descrizione categoria_B");
        $manager->persist($category_2);

        $tag_1 = new Tag();
        $tag_1->setName("tag_A");
        $tag_1->setDescription("Descrizione tag_A");
        $manager->persist($tag_1);

        $tag_2 = new Tag();
        $tag_2->setName("tab_B");
        $tag_2->setDescription("Descrizione tag_B");
        $manager->persist($tag_2);

        $tag_2 = new Tag();
        $tag_2->setName("tag_C");
        $tag_2->setDescription("Descrizione tag_C");
        $manager->persist($tag_2);

        $author = new Author();
        $author->setName('autore');
        $author->setTitle('title');
        $author->setCompany('company');
        $author->setUsername('username');
        $author->setShortBio('bio autore');
        $author->setPhone('1234567890');
        $author->setFacebook('facebook');
        $author->setTwitter('twitter');
        $author->setGithub('github');
        $author->setLinkedin('github');
        $manager->persist($author);

        $post_1 = new Post();
        $post_1->setTitle('Titolo A');
        $post_1->setSlug('titolo-a');
        $post_1->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');
        $post_1->setBody('Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32. The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.');
        $post_1->setAuthor($author);
        $post_1->setCategory($category_1);
        $post_1->addTag($tag_1);
        $manager->persist($post_1);

        $post_2 = new Post();
        $post_2->setTitle('Titolo B');
        $post_2->setSlug('titolo-b');
        $post_2->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');
        $post_2->setBody('Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32. The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.');
        $post_2->setAuthor($author);
        $post_2->setCategory($category_2);
        $post_2->addTag($tag_2);
        $manager->persist($post_2);

        $manager->flush();
    }
}
