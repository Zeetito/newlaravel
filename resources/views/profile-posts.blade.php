<x-layout>
        <div class="container py-md-5 container--narrow">
                <h2>
                <img class="avatar-small" src="{{$user->avatar}}" /> {{$user->username}}
                  <form class="ml-2 d-inline" action="#" method="POST">
                    <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                    <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
                  </form>
                </h2>
          
                <div class="profile-nav nav nav-tabs pt-2 mb-4">
                <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{$posts->count()}}</a>
                  <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
                  <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
                  @can('update',$user)
                    <a class='btn btn-secondary btn-small' href="/manage-avatar" class="profile-nav-link nav-item nav-link">Manage Avatar</a>
                  @endcan
                      
                </div>
                <div class="list-group">
                @foreach($posts as $post)
                <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{$post->user->avatar}}" />
                  <strong>{{$post->title}}</strong> on {{$post->created_at->format('Y-M-d')}}
                </a>

                @endforeach
                 
                </div>
              </div>
</x-layout>