<header class="header-fixed">
    <div class="header-first-bar">
        <div class="header-limiter">
            <div id="headerleft" class="headcomp">
                <a href="#" class="logo">
                    <div id="myLogo"></div>
                </a>
            </div>
            <div id="headermid" class="headcomp">
                <span id="htitle"></span>
                <span id="hsubtitle"></span>
            </div>
            <div id="headerright" class="headcomp">
                <div class="control">
                    <div id="buttonbox" class="headcomp">
                        <a id="activate-grid" class="headbutton fa-pencil" title="Edit dashboard"></a>
                        <a class="headbutton fa-sign-out" href="/auth/logout" title="Logout"></a>
                    </div>
                    @if (Auth::user() != null && Auth::user()->avatar != null)
                    <div id="avatarbox" class="headcomp useravatar-img" style="background-image: url({{Auth::user()->avatar}})">
                    </div>
                    @else
                    <div id="avatarbox" class="headcomp">
                        <a class="useravatar fa-user-secret"></a>
                    </div>
                    @endif
                    <div id="userinfobox" class="headcomp">
                        <span id="usernick">{{ Auth::user()->username }}</span>
                        <span id="username">{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>