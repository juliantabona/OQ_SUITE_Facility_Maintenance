(function($) {
    'use strict';
    $(function() {
        if ($('#calendar').length) {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultDate: '2018-07-01',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                //eventLimit: true, // allow "more" link when too many events
                eventRender: function(eventObj, $el) {
                    $el.popover({
                      title: eventObj.title,
                      content: eventObj.description,
                      trigger: 'hover',
                      placement: 'top',
                      container: 'body'
                    });
                  },
                events: [{
                        id: 1,
                        description: 'Installation of new split air conditioners at Ministry Of Health. [Priority: High] [Status: Open]',
                        title: 'Installation of new aircons in offices',
                        url: '/jobcards/1',
                        start: '2018-07-01',
                        end: '2018-07-03'
                    },
                    {
                        id: 2,
                        description: 'Repairs leaking water pipes in garden at Water Utilities. [Priority: High] [Status: Open]',
                        title: 'Repair of broken water pipe',
                        url: '/jobcards/1',
                        start: '2018-07-07',
                        end: '2018-07-10'
                    },
                    {
                        id: 5,
                        description: 'Repair of elevator doors refusing to close at Land Board. [Priority: High] [Status: Open]',
                        title: 'Repair & Maintenance of elevators',
                        url: '/jobcards/1',
                        start: '2018-07-11',
                        end: '2018-07-12'
                    }
                ]
            })
        }
    });
})(jQuery);
