<!DOCTYPE html>
<html>
<head>
    @include('shared.head')
    @include('shared.links')
</head>
<body>
<div id="app">
    <f7-statusbar></f7-statusbar>

    <f7-panel right cover>
        <f7-block>
            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item class="panel-close" link="/sessions" title="Sessions"></f7-list-item>
                    <f7-list-item class="panel-close" link="/workouts" title="Workouts"></f7-list-item>
                    <f7-list-item class="panel-close" link="/exercises/" title="Exercises"></f7-list-item>
                </f7-list-group>
            </f7-list>
        </f7-block>

        <f7-block>
            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item class="panel-close" link="/workouts" title="Help"></f7-list-item>
                    <f7-list-item class="panel-close" link="/sessions" title="Privacy"></f7-list-item>
                    <f7-list-item class="panel-close" link="/workouts" title="Credits"></f7-list-item>
                    <f7-list-item class="panel-close" link="/exercises/" title="jennyswiftcreations.com"></f7-list-item>
                </f7-list-group>
            </f7-list>
        </f7-block>

    </f7-panel>

    <f7-view id="main-view" main>

    </f7-view>
</div>

{{--@include('shared.footer.footer')--}}
@include('shared.scripts')
</body>
</html>
