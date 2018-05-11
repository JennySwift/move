import helpers from './Helpers'
export default {
    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findById: function (array, id) {
        var index = this.findIndexById(array, id);
        return array[index];
    },

    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findIndexById: function (array, id) {
        // return _.indexOf(array, _.findWhere(array, {id: id}));
        //So it still work if id is a string
        return _.indexOf(array, _.find(array, function (item) {
            return item.id == id;
        }));
    },

    /**
     *
     * @param array
     * @param id
     */
    deleteById: function (array, id) {
        var index = helpers.findIndexById(array, id);
        array = _.without(array, array[index]);

        return array;
    },

    deleteFromArray: function (itemToDelete, array) {
        var updatedArray = helpers.deleteById(array, itemToDelete.id);
        // object.set(array, '', updatedArray);
        // return object;
        return updatedArray;
    },

    /**
     *
     * @param item
     * @param array
     * @returns {*}
     */
    updateItemInArray: function (item, array) {
        var index = helpers.findIndexById(array, item.id);
        array[index] = item;
        //array should now be updated
        return array;
    },
}