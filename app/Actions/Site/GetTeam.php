<?php
namespace App\Actions\Site;
use App\Models\About;
use App\Models\TeamMember;

class GetTeam
{
  public function execute(): array
  {
    return [
      'about' => About::with('publishedImages')->first(),
      'teamMembers' => TeamMember::with('resumes')->flagged('isPublish')->orderBy('id')->get(),
    ];
  }
}
