/* Simple Javasctipt/JQuery MVC Framework - JSFrame 
 * Copyright (C) <2013>  Shamim Ahmed
 * CodeRangers LLC
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

//Author: Shamim Ahmed
//shamim@coderangers.com
//08/06/2013

/*global jQuery */

jQuery(function($) {
    'use strict';

    var Template = {
        videolist: ""
    }
	var _JF =[];
    var JSFrame = _JF =  {
		version:1.0,
        uuid: function() {
            /*jshint bitwise:false */
            var i, random;
            var uuid = '';
            for (i = 0; i < 32; i++) {
                random = Math.random() * 16 | 0;
                if (i === 8 || i === 12 || i === 16 || i === 20) {
                    uuid += '-';
                }
                uuid += (i === 12 ? 4 : (i === 16 ? (random & 3 | 8) : random)).toString(16);
            }
            return uuid;
        },
        pluralize: function(count, word) {
            return count === 1 ? word : word + 's';
        },
        Log: function(data) {        
			 if (window.console && window.console.log) {
           		 window.console.log(data);
			} else if (window.opera && window.opera.postError) {
				window.opera.postError(data);
			}
		
        },initLoader: function(el) {
            //	el.before('<div class="progress progress-success progress-striped"><div class="bar" style="width: 100%"></div></div>')
            el.parent().parent().css('position', 'relative');
            el.before('<div class="widget-box-layer" style="width:100%"><i class="icon-spinner icon-spin icon-2x white"></i></div>')

        },
        alertMessage: function(el, type, message) {
            el.before('<div class="alert alert-' + type + '">' + message + '</div>')
            setTimeout(function() {
                JSFrame.destroy(".alert");
            }, 3000);
        },
        setNewToken: function(token) {
            $("input[name='token']").val(token);
        },
        destroy: function(el) {
            $(el).remove();
        },
        reset: function(el) {
            $(el)[0].reset();
        },
        hideModal: function(modal, time) {
            setTimeout(function() {
                $('#' + modal).modal('hide');
            }, time);
        },
        showModal: function(modal) {
            setTimeout(function() {
                $('#' + modal).modal('show');
            }, time);
        },
        Alert: function(data) {
            alert(data);
        },
        Store: function(namespace, data) {
            if (arguments.length > 1) {
                return localStorage.setItem(namespace, JSON.stringify(data));
            } else {
                var store = localStorage.getItem(namespace);
                return (store && JSON.parse(store)) || [];
            }
        },
        animatedSkillBar: function() {
            $('.progress-skills').each(function() {
                var t = $(this),
                        label = t.attr('data-label');
                percent = t.attr('data-percent') + '%';
                t.find('.bar').text(label + ' ' + '(' + percent + ')').animate({width: percent});
            });
        },
        Render: function(el, data, template) {
            $('#' + el).html(tmpl(template, data));
            JSFrame.Localize();
        },
        Ajax: function(el, data, responseHandler) {		
            JSFrame.initLoader(el);
            var request = $.ajax({
                url: App.url,
                type: App.type,
                data: data,
                dataType: App.dataType
            });
            request.done(function(response) {
                JSFrame.destroy(".widget-box-layer");
                App[responseHandler].apply(this, [{"element": el.selector, "response": response}]);
            });
            request.fail(function(jqXHR, textStatus) {
                JSFrame.destroy(".widget-box-layer");
                JSFrame.Log("Request failed: " + textStatus);
            });

        },
        responsiveVideoPlayer: function() {
            $(".js-video").fitVids();
        },
        scrollEffect: function() {
            scrollPos = $(this).scrollTop();
            $('#landingSlide').css({'background-position': 'center ' + (200 + (scrollPos / 4)) + "px"});
        },
        scrollEffectInit: function() {
            $(window).scroll(function() {
                this.scrollEffect;
            });
        },
        getPageName: function() {
            return window.location.pathname.split('/')[1];
        },
        getPageAnchor: function() {
            return window.location.hash;
        },
		setGoToTop: function(){		
		    $("#totop").click(function() {
                $("html, body").animate({
                    scrollTop: 0
                }, 300);
                return false;
            });	
		}, 
        addValidation: function(form, callbacks) {
            $('form[name="' + form + '"]').find('input, textarea').not("[type=submit]").jqBootstrapValidation({
                submitSuccess: function($form, event) {
                    event.preventDefault();
                    App.Controllers[callbacks].apply(this);
                },
                filter: function() {
                    return $(this).is(":visible");
               }
            });
        },
        getLocale: function(str) {
            var newstr = '';
            if ($.isNumeric(str)) {

                for (var x = 0; x < str.length; x++)
                {
                    var c = $.i18n._(str.charAt(x));
                    newstr = newstr + c;
                }
                return newstr;
            } else {
                return $.i18n._(str);
            }
        },
        Localize: function() {

            $.each($('.i18n'), function() {
                $(this)._t($(this).html());
            });
            $.each($('.i18n-date'), function() {
                //$(this)._t($(this).html());
                var date = $(this).html().split(' ');
                var month = date[0];
                var day = date[1];
                day = day.replace(/,+$/, '');
                var year = date[2];
                console.log(month);
                date = JSFrame.getLocale(month) + ' ' + JSFrame.getLocale(day) + ', ' + JSFrame.getLocale(year);
                $(this).html(date);
            });
            $.each($('.i18n-n'), function() {
                $(this).html(JSFrame.getLocale($(this).html()));
            });

        },
        Confirm: function(heading, question, cancelButtonTxt, okButtonTxt, callback) {
            var confirmModal =
                    $('<div class="modal hide fade">' +
                    '<div class="modal-header">' +
                    '<a class="close" data-dismiss="modal" >&times;</a>' +
                    '<h3>' + heading + '</h3>' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '<p>' + question + '</p>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                    '<a href="#" class="btn" data-dismiss="modal">' +
                    cancelButtonTxt +
                    '</a>' +
                    '<a href="#" id="okButton" class="btn btn-primary">' +
                    okButtonTxt +
                    '</a>' +
                    '</div>' +
                    '</div>');

            confirmModal.find('#okButton').click(function(event) {
                callback();
                confirmModal.modal('hide');
            });

            confirmModal.modal('show');
        },
        LiveEdit: function(el) {
            console.log(el);
            var type = el.data('type');
            var tmpl1 = '<span class="editable-container editable-inline" style=""><div><div class="editableform-loading" style="display: none;"></div><form id="' + el.attr('rel') + '" class="form-inline editableform" style=""><div class="control-group"><div>';

            var tmpl3 = '<span class="editable-clear-x"></span></div><div class="editable-buttons"><button class="btn btn-primary editable-submit" type="submit"><i class="icon-ok icon-white"></i></button><button class="btn editable-cancel" type="button"><i class="icon-remove"></i></button></div></div><div class="editable-error-block help-block" style="display: none;"></div></div></form></div></span>';

            if (type == 'text') {
                var tmpl2 = '<div class="editable-input" style="position: relative;"><input type="text" style="padding-right: 24px;" value="' + el.html() + '" class="input-mini">';
				el.hide().before(tmpl1 + tmpl2 + tmpl3);
            }

            if (type == 'textarea') {
                var tmpl2 = '<div class="editable-input" style="position: relative;"><textarea class="input-large" placeholder="' + el.html() + '" rows="4">' + el.html() + '</textarea>';
                el.hide().before(tmpl1 + tmpl2 + tmpl3);
            }

            if (type == 'tags') {
                var tmpl2 = '<div class="editable-input" style="position: relative;width:165px;"><select data-placeholder="Select Tags" user-input="true" multiple class="chzn-select-width input-medium live-select" tabindex="16"> ';
                var option = '';
                $.each(el.attr('data-source').split(','), function(key, data) {

                    option += '<option selected="selected" value="' + data + '">' + data + '</option>';
                });

                tmpl2 += option + '</select>';
                el.hide().before(tmpl1 + tmpl2 + tmpl3);
                App.Library.Chosen();
            }
            if (type == 'select')
            {
                var tmpl2 = '<div class="editable-input" style="position: relative;"><select class="input-medium live-select"> ';
                var option = '<option value="">Select</option>';
                $.getJSON('/util/' + el.data('source'), function(data) {
                    $.each(data, function(key, cat) {
                        if (el.html() == cat.name)
                            option += '<option value="' + cat.name + '" selected>' + cat.name + '</option>';
                        else
                            option += '<option value="' + cat.name + '">' + cat.name + '</option>';
                    });
                    tmpl2 += option + '</select>';
                    el.hide().before(tmpl1 + tmpl2 + tmpl3);

                });
            }
        }
 
    };
	
    var App = {
        url: 'error',
        type: 'GET',
        data: 'init=1',
        dataType: 'JSON',
        VideoDataTable: null,
        init: function() {
            this.ENTER_KEY = 13;
            //this.todos = JSFrame.Store('Coderangers');
            this.cacheElements();
            this.bindEvents();
            //this.Render();
            JSFrame.animatedSkillBar();
            JSFrame.responsiveVideoPlayer();
            JSFrame.scrollEffectInit();
            JSFrame.Localize();
        },
        cacheElements: function() {
            this.$register = $("#register");
    
         }
        bindEvents: function() {
			JSFrame.addValidation('register','CreateNewUser');
        },
        completeRegistration: function(data) {
            JSFrame.Log(JSON.stringify(data));
            JSFrame.Log(data.response.message);
            JSFrame.alertMessage($(data.element), 'success', data.response.message);
            JSFrame.reset('#register');
        }
    };

	App.Controllers = {
		
			CreateNewUser: function() {
				App.url = '/register/create';
				App.type = 'POST';
				JSFrame.Ajax(App.$register, App.$register.serialize(), "completeRegistration");			
			}
		
	};
	
	
	
	App.Library ={

		 CreateFileUploader: function(el, ext, url, responseHandler) {

            var uploader = new qq.FineUploader({
                element: document.getElementById(el),
                request: {
                    endpoint: url
                },
                text: {
                    uploadButton: '<div><i class="icon-upload icon-white"></i><span class="i18n">Upload a file</div></div>'
                },
                template: '<div class="qq-uploader">' +
                        '<pre class="qq-upload-drop-area"><span>{dragZoneText}</span></pre>' +
                        '<div class="qq-upload-button btn btn-success" style="width: auto;">{uploadButtonText}</div>' +
                        '<span class="qq-drop-processing"><span>{dropProcessingText}</span><span class="qq-drop-processing-spinner"></span></span>' +
                        '<ul class="qq-upload-list" style="margin-top: 10px; text-align: center;"></ul>' +
                        '</div>',
                classes: {
                    success: 'alert alert-success',
                    fail: 'alert alert-error'
                },
                callbacks: {
                    onSubmit: function(id, fileName) {
                 },
                onComplete: function(id, fileName, responseJSON) {
                        if (responseJSON.success) {
                            App[responseHandler].apply(this, [{"response": responseJSON}]);
                        }
                    }
                }
            });

            return uploader;
        },
		Chosen: function() {

            var config = {
                '.chzn-select': {
                },
                '.chzn-select-deselect': {allow_single_deselect: true},
                '.chzn-select-no-single': {disable_search_threshold: 10},
                '.chzn-select-no-results': {no_results_text: 'Oops, nothing found!'},
                '.chzn-select-width': {width: "95%"}
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }

        },
	
	}; 
 
    App.init();
});