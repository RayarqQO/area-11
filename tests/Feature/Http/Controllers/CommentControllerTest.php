<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
class CommentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'store',
            \App\Http\Requests\CommentStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $text = $this->faker->word();
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->post(route('comment.store'), [
            'text' => $text,
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $comments = Comment::query()
            ->where('text', $text)
            ->where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->get();
        $this->assertCount(1, $comments);
        $comment = $comments->first();

        $response->assertRedirect(route('comment.index'));
        $response->assertSessionHas('comment.id', $comment->id);
    }


    /**
     * @test
     */
    public function edit_displays_view()
    {
        $comment = Comment::factory()->create();

        $response = $this->get(route('comment.edit', $comment));

        $response->assertOk();
        $response->assertViewIs('comment.edit');
        $response->assertViewHas('comment');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'update',
            \App\Http\Requests\CommentUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $comment = Comment::factory()->create();
        $text = $this->faker->word();
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->put(route('comment.update', $comment), [
            'text' => $text,
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $comment->refresh();

        $response->assertRedirect(route('comment.index'));
        $response->assertSessionHas('comment.id', $comment->id);

        $this->assertEquals($text, $comment->text);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
    }
}
