module.exports = function(grunt) {

  grunt.initConfig({
    acf_g: {
      assets: {
        path: {
          src: 'assets/src',
          build: 'assets'
        },
        paths: {
          js: {
            input: {
              src: '<%=acf_g.assets.path.src%>/js/**/*.js',
              dest: '<%=acf_g.assets.path.build%>/js/input.dev.js',
              min: '<%=acf_g.assets.path.build%>/js/input.js'
            }
          },
          sass: {
            input: {
              src: '<%=acf_g.assets.path.src%>/sass/input.scss',
              dest: '<%=acf_g.assets.path.build%>/css/input.css'
            }
          }
        }
      }
    },
    pkg: grunt.file.readJSON('package.json'),
    concat: {
      input: {
        options: {
          separator: '\n\n'
        },
        src: '<%=acf_g.assets.paths.js.input.src%>',
        dest: '<%=acf_g.assets.paths.js.input.dest%>'
      }
    },
    uglify: {
      dist: {
        files: {
          '<%=acf_g.assets.paths.js.input.min%>': ['<%=acf_g.assets.paths.js.input.dest%>']
        }
      }
    },
    sass: {
      dist: {
        options: {
          style: 'compressed',
          noCache: false,
          sourcemap: 'none'
        },
        files: {
          '<%=acf_g.assets.paths.sass.input.dest%>': '<%=acf_g.assets.paths.sass.input.src%>'
        }
      }
    },
    clean: {
      build: ['<%=acf_g.assets.paths.js.input.dest%>']
    },
    watch: {
      scss: {
        files: ['<%=acf_g.assets.path.src%>/sass/**/*.scss'],
        tasks: ['sass']
      },
      concat: {
        files: ['<%=acf_g.assets.path.src%>/js/**/*.js'],
        tasks: ['concat', 'uglify', 'clean']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', [
    'concat',
    'sass',
    'uglify',
    'clean',
    'watch'
  ]);
  
};