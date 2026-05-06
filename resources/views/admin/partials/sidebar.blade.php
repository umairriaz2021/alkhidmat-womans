 <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
          
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                 <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">Users</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.users')}}">All Users</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.create.user')}}">Create User</a></li>
                  
                </ul>
              </div>
            </li>
          
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                <i class="menu-icon mdi mdi-card-text-outline"></i>
                <span class="menu-title">Pages</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="form-elements">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="{{route('admin.all.pages')}}">All Pages</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{route('admin.add.pages')}}">Create Page</a></li>
                </ul>
              </div>
            </li>
             <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#posts-elements" aria-expanded="false" aria-controls="posts-elements">
                <i class="menu-icon mdi mdi-newspaper-variant-outline"></i>
                <span class="menu-title">Blogs</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="posts-elements">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="{{route('posts.index')}}">All Blogs</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{route('posts.create')}}">Add Category</a></li>

                </ul>
              </div>
            </li>
             <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#categories-elements" aria-expanded="false" aria-controls="categories-elements">
                <i class="menu-icon mdi mdi-label-multiple-outline"></i>
                <span class="menu-title">Categories</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="categories-elements">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="{{route('categories.index')}}">All Categories</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{route('categories.create')}}">Add Category</a></li>
                </ul>
              </div>
            </li>
             <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tags-elements" aria-expanded="false" aria-controls="tags-elements">
                <i class="menu-icon mdi mdi-label-multiple-outline"></i>
                <span class="menu-title">Tags</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="tags-elements">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="{{route('tags.index')}}">All Categories</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{route('tags.create')}}">Add Category</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#basic-templates" aria-expanded="false" aria-controls="form-elements">
                <i class="menu-icon mdi mdi-checkbox-multiple-blank-outline"></i>
                <span class="menu-title">Templates</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="basic-templates">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="{{route('admin.all.template')}}">All Templates</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{route('admin.add.templates')}}">Create Template</a></li>
                </ul>
              </div>
            </li>
             <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Slider</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.slider')}}">All Sliders</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.add.slider')}}">Create Slider</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">Menus</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.menus')}}">All Menus</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.create.menus')}}">Create Menu</a></li>
                </ul>
              </div>
            </li>
             <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Settings</span>
                 <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.settings')}}">Site Settings</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.show.megamenu')}}">All Mega Menus</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.create.megamenus')}}">Create Mega Menus</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </nav>