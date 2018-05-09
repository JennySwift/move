export default {


    /**
     *
     * @param exercise
     * @returns {{name, description: (*|string|Document.description), priority}}
     */
    setData: function (exercise) {
        var data = {
            name: exercise.name,
            description: exercise.description,
            priority: exercise.priority
        };

        return data;
    }
}