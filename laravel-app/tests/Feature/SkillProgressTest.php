<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\SkillProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillProgressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 進捗を追加できる()
    {
        $skill = Skill::factory()->create();

        $response = $this->post(route('skills.progresses.store', $skill), [
            'progress_date' => '2024-01-01',
            'title' => '新しい進捗',
            'content' => '進捗の内容',
        ]);

        $response->assertRedirect(route('skills.show', $skill));
        $response->assertSessionHas('status', '進捗を追加しました');

        $this->assertDatabaseHas('skill_progresses', [
            'skill_id' => $skill->id,
            'progress_date' => '2024-01-01',
            'title' => '新しい進捗',
            'content' => '進捗の内容',
        ]);
    }

    /** @test */
    public function 進捗を編集できる()
    {
        $skill = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill->id,
            'progress_date' => '2024-01-01',
            'title' => '元のタイトル',
            'content' => '元の内容',
        ]);

        $response = $this->put(route('skills.progresses.update', [$skill, $progress]), [
            'progress_date' => '2024-01-02',
            'title' => '更新されたタイトル',
            'content' => '更新された内容',
        ]);

        $response->assertRedirect(route('skills.show', $skill));
        $response->assertSessionHas('status', '進捗を更新しました');

        $this->assertDatabaseHas('skill_progresses', [
            'id' => $progress->id,
            'progress_date' => '2024-01-02',
            'title' => '更新されたタイトル',
            'content' => '更新された内容',
        ]);
    }

    /** @test */
    public function 他のスキルの進捗を編集できない()
    {
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill1->id,
        ]);

        $response = $this->put(route('skills.progresses.update', [$skill2, $progress]), [
            'progress_date' => '2024-01-02',
            'title' => '更新されたタイトル',
            'content' => '更新された内容',
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function 進捗の編集時にバリデーションエラーが発生する()
    {
        $skill = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill->id,
        ]);

        $response = $this->put(route('skills.progresses.update', [$skill, $progress]), [
            'progress_date' => '',
            'title' => '',
            'content' => '内容',
        ]);

        $response->assertSessionHasErrors(['progress_date', 'title']);
    }

    /** @test */
    public function 進捗を削除できる()
    {
        $skill = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill->id,
        ]);

        $response = $this->delete(route('skills.progresses.destroy', [$skill, $progress]));

        $response->assertRedirect(route('skills.show', $skill));
        $response->assertSessionHas('status', '進捗を削除しました');

        $this->assertDatabaseMissing('skill_progresses', [
            'id' => $progress->id,
        ]);
    }

    /** @test */
    public function 他のスキルの進捗を削除できない()
    {
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill1->id,
        ]);

        $response = $this->delete(route('skills.progresses.destroy', [$skill2, $progress]));

        $response->assertStatus(404);

        $this->assertDatabaseHas('skill_progresses', [
            'id' => $progress->id,
        ]);
    }
}

