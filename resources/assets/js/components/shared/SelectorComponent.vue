<template>
    <f7-popup class="selector-popup" :id="id">
        <f7-page with-subnavbar>
            <f7-navbar>
                <f7-nav-title>{{popupTitle}}</f7-nav-title>
                <f7-nav-right>
                    <f7-link @click="closePopup">Close</f7-link>
                </f7-nav-right>

                <!--Searchbar for database-->
                <f7-subnavbar v-if="url">
                    <f7-searchbar :custom-search="true" @searchbar:search="onDatabaseSearch" @input="onInput" :backdrop="false">
                        <div slot="inner-end">
                            <f7-button v-on:click="searchDatabase()">Go</f7-button>
                        </div>
                    </f7-searchbar>
                </f7-subnavbar>

                <!--Searchbar for local options-->
                <f7-subnavbar v-if="options">
                    <f7-searchbar :custom-search="true" @searchbar:search="onLocalSearch" @input="onInput" :backdrop="false" :disable-button="false">

                    </f7-searchbar>
                </f7-subnavbar>
            </f7-navbar>



            <f7-list no-hairlines-md contacts-list>
                <f7-list-item v-if="any" v-on:click="selectOption(false)">Any</f7-list-item>
                <f7-list-item v-for="option in filteredOptions" :key="option.id" v-on:click="selectOption(option)">
                    <span v-if="displayProp">{{option[displayProp]}}</span>
                    <span v-if="!displayProp">{{option}}</span>
                </f7-list-item>
            </f7-list>

            <f7-toolbar v-if="url && shared.selectorOptions.pagination" class="flex-container">
                <span class="pagination-info">Page {{shared.selectorOptions.pagination.current_page}} of {{shared.selectorOptions.pagination.last_page}}</span>
                <f7-button @click="prevPage()" v-bind:disabled="!shared.selectorOptions.pagination.prev_page_url" class="btn btn-warning">Prev</f7-button>
                <f7-button @click="nextPage()" v-bind:disabled="!shared.selectorOptions.pagination.next_page_url" class="btn btn-warning">Next</f7-button>
            </f7-toolbar>

        </f7-page>
    </f7-popup>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                searchTerm: '',
//                mutableOptions: []
            }
        },
        computed: {
            filteredOptions: function () {
                var that = this;
                return _.filter(this.options, function (option) {
                  return option[that.displayProp].toLowerCase().indexOf(that.searchTerm.toLowerCase()) !== -1;
                });
            }
        },
        methods: {
            prevPage: function () {
                this.searchDatabase(this.shared.selectorOptions.pagination.current_page-1);
            },

            nextPage: function () {
                this.searchDatabase(this.shared.selectorOptions.pagination.current_page+1);
            },

            searchDatabase: function (pageNumber) {
                var url = this.url + '?' + this.fieldToFilterBy  + '=' + this.searchTerm;
                if (pageNumber) {
                    url+='&page='+ pageNumber;
                }
                helpers.get({
                    url:  url,
                    storeProperty: 'selectorOptions',
                    callback: function (response) {
//                        this.mutableOptions = response.data;
                    }.bind(this)
                });
            },

            onDatabaseSearch: function (event) {
                var searchTerm = event.value;
                this.searchTerm = searchTerm;
            },
            onLocalSearch: function (event) {
                this.searchTerm = event.value;
            },
            onInput: function (event) {
                var key = event.data;
            },
            closePopup: function () {
                store.closePopup('#' + this.id);
            },
            selectOption: function (option) {
                if (this.model) {
                    this.$emit('update:model', option);
                }
                if (this.path) {
                    store.set(option, this.path);
                }
                if (this.onSelect) {
                    this.onSelect(option);
                }
//                this.$emit('selected', option);
                this.closePopup();
            }
        },
        props: {
            displayProp: {
//                default: 'name'
            },
            popupTitle: {
                default: 'Popup'
            },
            options: {},
            model: {},
            path: {},
            id: {},
            //Add 'Any' option before the other options
            any: {},
            onSelect: {},
            url: {},
            fieldToFilterBy: {}
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>