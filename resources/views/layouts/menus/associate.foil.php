<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="<?php if( Auth::check() && Auth::user()->isSuperUser() ): ?>container-fluid<?php else: ?>container<?php endif; ?>">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= url('') ?>">
                <?= config('identity.sitename' ) ?>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li {if $controller eq "dashboard"}class="active"{/if}>
                    <a href="{genUrl}">Home</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Member Information <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= url('') ?>/switch/configuration">Switch Configuration</a>
                        </li>
                        <li>
                            <a href="<?= url('') ?>/customer/details">Member Details</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Peering<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if( !config( 'ixp_fe.frontend.disabled.peering-matrix', false ) ): ?>
                            <li><a href="<?= url('') ?>/peering-matrix">Public Peering Matrix</a></li>
                        <?php endif; ?>
                        <?php if( !config('ixp_fe.frontend.disabled.lg' ) ): ?>
                            <li><a href="<?= url('lg') ?>">Looking Glass</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <?php
                    // STATIC DOCUMENTATION LINKS - SPECIFIC TO INDIVIDUAL IXPS
                    // Add a skinned file in views/_skins/xxx/header-documentation.phtml for your IXP to override the sample
                    echo $this->insert('header-documentation');
                ?>

                <li class="dropdown <?= !request()->is( 'statistics/*', 'weather-map/*' ) ?: 'active' ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Statistics<b class="caret"></b></a>
                    <ul class="dropdown-menu">

                        <?php if( config( 'grapher.access.ixp', Entities\User::AUTH_PUBLIC ) <= Auth::user()->getPrivs() ): ?>
                            <li>
                                <a href="<?= route( 'statistics/ixp' ) ?>">Overall Peering Graphs</a>
                            </li>
                        <?php endif; ?>
                        <?php if( config( 'grapher.access.infrastructure', Entities\User::AUTH_PUBLIC )  <= Auth::user()->getPrivs() ): ?>
                            <li>
                                <a href="<?= route( 'statistics/infrastructure' ) ?>">Infrastructure Graphs</a>
                            </li>
                        <?php endif; ?>
                        <?php if( config( 'grapher.access.trunk', Entities\User::AUTH_PUBLIC ) <= Auth::user()->getPrivs() ): ?>
                            <li>
                                <a href="<?= route('statistics/trunk') ?>">Inter-Switch / PoP Graphs</a>
                            </li>
                        <?php endif; ?>
                        <?php if( config( 'grapher.access.switch', Entities\User::AUTH_PUBLIC ) <= Auth::user()->getPrivs() ): ?>
                            <li>
                                <a href="<?= route('statistics/switch') ?>">Switch Aggregate Graphs</a>
                            </li>
                        <?php endif; ?>

                        <?php if( is_array( config( 'ixp_tools.weathermap', false ) ) ): ?>

                            <li class="divider"></li>

                            <?php foreach( config( 'ixp_tools.weathermap' ) as $k => $w ): ?>
                                <li>
                                    <a href="<?= route( 'weathermap' , [ 'id' => $k ] ) ?>"><?= $w['menu'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>

                <li class="">
                    <a href="<?= route( 'public-content', [ 'page' => 'support' ] ) ?>">Support</a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= url( '/profile' ) ?>">Profile</a>
                        </li>
                        <li>
                            <a href="<?= url( '/api-key' ) ?>">API Keys</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php url( 'auth/logout' ) ?>">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= url( 'auth/logout' ) ?>">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
