module.exports = function (grunt) {
    grunt.initConfig({
        sass: {
            dist: {     
                files: {
                    'css/screen.css': 'sass/screen.scss',
                    'css/boom.css': 'sass/boom.scss'
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