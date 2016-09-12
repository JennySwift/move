@include('shared.footer.real-footer')

{{--Exercises--}}
@include('exercises-page-component')
@include('new-exercise-component')
@include('new-series-component')
@include('exercise-units-page-component')
@include('exercise-series-history-popup-component')
@include('exercise-series-popup-component')
@include('exercise-popup-component')
@include('exercise-entries-component')
@include('new-exercise-entry-component')
@include('entries-for-specific-exercise-and-date-and-unit-popup-component')

{{--Shared--}}
@include('shared.feedback-component')
@include('shared.loading-component')
@include('shared.popup-component')
@include('shared.autocomplete-component')
@include('shared.date-navigation-component')
@include('shared.header.navbar-component')

<script type="text/javascript" src="/build/js/bundle.js"></script>