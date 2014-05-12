module.exports = function (grunt) {
    // Do grunt-related things in here
    grunt.initConfig({
        watch:{
            files:['**/*.php'],
            tasks:['shell']
        },
        shell:{
            runPhp:{
                command:'php boom.php'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-shell');
};