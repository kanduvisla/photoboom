module.exports = function (grunt) {
    // Do grunt-related things in here
    //grunt.initConfig({
    //    watch:{
    //        files:['**/*.php'],
    //        tasks:['shell']
    //    },
    //    shell:{
    //        runPhp:{
    //            command:'php boom.php'
    //        }
    //    }
    //});
    //
    //grunt.loadNpmTasks('grunt-contrib-watch');
    //grunt.loadNpmTasks('grunt-shell');
    
    grunt.initConfig({
        sass: {
            dist: {     
                files: {
                    'css/screen.css': 'sass/screen.scss'
                }
            }
        },
        watch: {
            options: {
                livereload : true
            },
            files: ['sass/**/*.scss'],
            tasks: ['sass:dist']
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
};