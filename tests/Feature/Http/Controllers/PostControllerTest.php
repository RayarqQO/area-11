<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PostController
 */
class PostControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->get(route('post.index'));

        $response->assertOk();
        $response->assertViewIs('post.index');
        $response->assertViewHas('posts');
    }


    /**
     * @test
     */
    public function create_displays_view()
    {
        $response = $this->get(route('post.create'));

        $response->assertOk();
        $response->assertViewIs('post.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PostController::class,
            'store',
            \App\Http\Requests\PostStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $title = $this->faker->sentence(4);
        $user = User::factory()->create();
        $image = $this->faker->word();
        $description = $this->faker->text();
        $content = $this->faker->paragraphs(3, true);
        $views = $this->faker->numberBetween(-10000, 10000);
        $score = $this->faker->randomFloat(/** decimal_attributes **/);
        $is_featured = $this->faker->boolean();
        $slug = $this->faker->slug();

        $response = $this->post(route('post.store'), [
            'title' => $title,
            'user_id' => $user->id,
            'image' => $image,
            'description' => $description,
            'content' => $content,
            'views' => $views,
            'score' => $score,
            'is_featured' => $is_featured,
            'slug' => $slug,
        ]);

        $posts = Post::query()
            ->where('title', $title)
            ->where('user_id', $user->id)
            ->where('image', $image)
            ->where('description', $description)
            ->where('content', $content)
            ->where('views', $views)
            ->where('score', $score)
            ->where('is_featured', $is_featured)
            ->where('slug', $slug)
            ->get();
        $this->assertCount(1, $posts);
        $post = $posts->first();

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('post.id', $post->id);
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('post.show', $post));

        $response->assertOk();
        $response->assertViewIs('post.show');
        $response->assertViewHas('post');
    }


    /**
     * @test
     */
    public function edit_displays_view()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('post.edit', $post));

        $response->assertOk();
        $response->assertViewIs('post.edit');
        $response->assertViewHas('post');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PostController::class,
            'update',
            \App\Http\Requests\PostUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $post = Post::factory()->create();
        $title = $this->faker->sentence(4);
        $user = User::factory()->create();
        $image = $this->faker->word();
        $description = $this->faker->text();
        $content = $this->faker->paragraphs(3, true);
        $views = $this->faker->numberBetween(-10000, 10000);
        $score = $this->faker->randomFloat(/** decimal_attributes **/);
        $is_featured = $this->faker->boolean();
        $slug = $this->faker->slug();

        $response = $this->put(route('post.update', $post), [
            'title' => $title,
            'user_id' => $user->id,
            'image' => $image,
            'description' => $description,
            'content' => $content,
            'views' => $views,
            'score' => $score,
            'is_featured' => $is_featured,
            'slug' => $slug,
        ]);

        $post->refresh();

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('post.id', $post->id);

        $this->assertEquals($title, $post->title);
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals($image, $post->image);
        $this->assertEquals($description, $post->description);
        $this->assertEquals($content, $post->content);
        $this->assertEquals($views, $post->views);
        $this->assertEquals($score, $post->score);
        $this->assertEquals($is_featured, $post->is_featured);
        $this->assertEquals($slug, $post->slug);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects()
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('post.destroy', $post));

        $response->assertRedirect(route('post.index'));

        $this->assertDeleted($post);
    }
}
