import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);
import swal from 'sweetalert2'
// var store = require('./Store');
import store from './Store'
import helpers from './Helpers'





export default {
    /**
     * storeProperty is the store property to set once the items are loaded.
     * loadedProperty is the store property to set once the items are loaded, to indicate that the items are loaded.
     * todo: allow for sending data: add {params:data} as second argument
     */
    get: function (options) {
        store.showLoading();
        axios.get(options.url)
            .then(function (response) {
                if (options.storeProperty) {
                    if (options.updatingArray) {
                        //Update the array the item is in
                        store.update(response.data, options.storeProperty);
                    }
                    else if (options.pagination) {
                        store.set(response.data, options.storeProperty);
                    }
                    // else {
                    //     //Allow for pagination
                    //     var data = response.data.data ? response.data.data : response.data;
                    //     store.set(data, options.storeProperty);
                    // }
                }

                if (options.callback) {
                    options.callback(response.data);
                }

                if (options.loadedProperty) {
                    store.set(true, options.loadedProperty);
                }

                store.hideLoading();
        })
        .catch(function (error) {
            helpers.handleResponseError(error.response);
        });
    },

    /**
     * options:
     * array: store array to add to
     */
    post: function (options) {
        store.showLoading();
        var that = this;
        axios.post(options.url, options.data)
            .then(function (response) {
                if (options.property) {
                    store.set(response.data, options.property);
                }

                if (options.callback) {
                    options.callback(response.data);
                }

                store.hideLoading();

                if (options.message) {
                    app.__vue__.$bus.$emit('provide-feedback', options.message, 'success');
                }

                if (options.array) {
                    store.add(response.data, options.array);
                }

                if (options.clearFields) {
                    options.clearFields();
                }

                if (options.redirectTo) {
                    that.getRouter().push(options.redirectTo);
                }
        })
        .catch(function (error) {
            helpers.handleResponseError(error.response);
        });
    },

    /**
     *
     */
    put: function (options) {
        store.showLoading();
        var that = this;
        axios.put(options.url, options.data)
            .then(function (response) {
                if (options.callback) {
                    options.callback(response.data);
                }

                store.hideLoading();

                if (options.message) {
                    app.__vue__.$bus.$emit('provide-feedback', options.message, 'success');
                }

                if (options.property) {
                    store.update(response.data, options.property);
                }

                if (options.redirectTo) {
                    that.getRouter().push(options.redirectTo);
                }
                helpers.hidePopup();

            })
            .catch(function (error) {
                helpers.handleResponseError(error.response);
            })
    },

    /**
     *
     */
    delete: function (options) {
        var that = this;

        if (options.noConfirm) {
            //Do not confirm before deleting
            //"this" refers to Helpers.js which called this method
            this.requests.proceedWithDelete(options);
        }
        else {
            //Confirm before deleting
            var confirmTitle = options.confirmTitle ? options.confirmTitle : 'Are you sure?';
            swal({
                title: confirmTitle,
                text: options.confirmText,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-default',
                buttonsStyling: false,
                reverseButtons: true,
                showCloseButton: true,
                animation: false
            }).then(function() {
                that.requests.proceedWithDelete(options);
            });
        }
    },

    proceedWithDelete (options) {
        store.showLoading();

        if (options.beforeDelete) {
            //Code to run before deleting
            options.beforeDelete();
        }

        axios.delete(options.url)
            .then(function (response) {
                if (options.callback) {
                    options.callback(response);
                }

                store.hideLoading();

                if (options.message) {
                    app.__vue__.$bus.$emit('provide-feedback', options.message, 'success');
                }

                if (options.array) {
                    store.delete(options.itemToDelete, options.array);
                }

                if (options.redirectTo) {
                    helpers.getRouter().push(options.redirectTo);
                }
            })
            .catch(function (error) {
                if (options.onFail) {
                    options.onFail();
                }
                helpers.handleResponseError(error.response);
            });
    }
}