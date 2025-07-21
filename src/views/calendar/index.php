<?php
use yii\web\View;
use yii\bootstrap5\Modal;
use app\assets\StyleAsset;
use app\assets\AppAsset;

/* @var $this View */

AppAsset::register($this);
StyleAsset::register($this);
$this->title = 'Календарь событий';

$this->registerJsVar('csrfToken', Yii::$app->request->csrfToken);
?>

<body>

<div id='calendar'></div>

</body>
<!-- Модальное окно -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" id="modalTitle" class="modal-title form-control">
                <input type="hidden" class="form-control" id="id_db" >
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="eventTypeRead" >
                </div>
                <div class="mb-3">
                    <label>Начало</label>
                    <input type="datetime-local" id="modalStart" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Окончание:</label>
                    <input type="datetime-local" id="modalEnd" class="form-control">
                </div>
                <div class="mb-3">
                    <textarea id="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="editEventBtn">Редактировать</button>
                <button type="button" class="btn btn-danger" id="deleteEventBtn">Удалить</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно создания события -->
<div class="modal fade" id="createEventModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Новое событие</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="mb-3">
                        <select class="form-control" id="eventType" required>
                            <option value="0">Событие</option>
                            <option value="1">Задача</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата начала</label>
                        <input type="datetime-local" class="form-control" id="eventStart" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата окончания</label>
                        <input type="datetime-local" class="form-control" id="eventEnd" required>
                    </div>
                    <textarea id="ModalDescription" class="form-control"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="saveEvent">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
    function formatDateForInput(date) {
        if (!date) return ''; // Handle null/undefined
        const d = new Date(date);
        // Format to YYYY-MM-DDThh:mm
        return d.toISOString().slice(0, 16); // Slice to get YYYY-MM-DDThh:mm
    }
    // Инициализация календаря
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        let data = {};


        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'multiMonthYear,dayGridMonth,timeGridWeek'
            },
            locale: 'ru',
            initialView: 'multiMonthYear',
            initialDate: '<?=date("Y-m-d\TH:i:s")?>',
            editable: true,
            selectable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            // multiMonthMaxColumns: 1, // guarantee single column
            // showNonCurrentDates: true,
            // fixedWeekCount: false,
            // businessHours: true,
            // weekends: false,
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '/calendar/get-event', // Укажите ваш URL обработчика
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        update_event: '1',
                        _csrf: csrfToken
                    },
                    success: function(response) {
                        var events = [];
                        let number = 1;
                        $.each(response.message, function(index, event) {
                            events.push({
                                id: number++,
                                title: event.title,
                                start: event.start,
                                end: event.end,
                                color: event.color,
                                extendedProps: {
                                    description:event.description,
                                    type: event.type,
                                    id_db: event.id
                                }
                            });
                        });
                        console.log(events);

                        successCallback(events);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr,status,error)
                    }
                });
            },
            eventClick: function(info) {
                // info.event содержит данные о событии
                const eventData = info.event;
                // Пример: заполнение модального окна данными
                $('#modalTitle').val(eventData.title);
                $('#modalStart').val(formatDateForInput(eventData.start));
                if (eventData.end) {
                    $('#modalEnd').val(formatDateForInput(eventData.end));
                } else {
                    $('#modalEnd').val('Нет даты окончания');
                }

                $('#description').text(eventData.extendedProps.description);
                $('#eventTypeRead').text(eventData.extendedProps.type);
                $('#id_db').text(eventData.extendedProps.id_db)

                // Показываем модальное окно
                $('#eventModal').modal('show');

                // Можно добавить кнопки действий
                $('#editEventBtn').off('click').on('click', function()
                {
                    const Event = {
                        title_event:  $('#modalTitle').val(),
                        description: $('#description').val(),
                        start_event: $('#modalStart').val(),
                        end_event: $('#modalEnd').val(),
                        type: eventData.extendedProps.type,
                        id: eventData.extendedProps.id_db
                    }
                    $('#eventModal').modal('hide');

                    $.ajax({
                        url: '/calendar/update-event', // Укажите ваш URL обработчика
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            event: Event,
                            _csrf: csrfToken
                        },
                        success: function(response) {
                             calendar.refetchEvents();

                        },
                        error: function(xhr) {
                            console.log(xhr);
                        }
                    });
                });

                $('#deleteEventBtn').off('click').on('click', function() {
                    if (confirm('Удалить это событие?'))
                    {
                        const eventData = info.event;
                        $('#eventModal').modal('hide');
                        $.ajax({
                            url: '/calendar/delete-event', // Укажите ваш URL обработчика
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                delete:  1,
                                id_db: eventData.extendedProps.id_db,
                                type: eventData.extendedProps.type,
                                _csrf: csrfToken
                            },
                            success: function(response) {
                                console.log('response_answer');
                                console.log(response);
                                calendar.refetchEvents();

                            },
                            error: function(xhr) {
                                console.log(xhr);
                            }
                        });

                    }
                });

                // Отменяем стандартное поведение
                info.jsEvent.preventDefault();
            },
            dateClick: function(info) {
                // Открываем модалку при клике на дату
                $('#eventStart').val(info.dateStr + 'T00:00');
                $('#createEventModal').modal('show');
            },
        });

        calendar.render();

        $('#saveEvent').click(function() {
            if (!$('#eventForm')[0].checkValidity()) {
                $('#eventForm')[0].reportValidity();
                return;
            }

            let type_info = $('#eventType').val()
            let color ='#4977cc';
            if(type_info == 0)
            {
                color ='#4977cc';
            }
            else
            {
                color = '#dc6f35';
            }


            const newEvent = {
                title: $('#eventTitle').val(),
                description: $('#ModalDescription').val(),
                start: $('#eventStart').val(),
                end: $('#eventEnd').val(),
                color: color,
                allDay: !$('#eventStart').val().includes('T'),
                extendedProps: {
                    description: $('#ModalDescription').val(),
                    type: $('#eventType').val()
                }
            }

            calendar.addEvent(newEvent);
            $('#createEventModal').modal('hide');

            const Event = {
                title_event:  $('#eventTitle').val(),
                description: $('#ModalDescription').val(),
                start_event: $('#eventStart').val(),
                end_event: $('#eventEnd').val(),
                type: $('#eventType').val(),
            }
            console.log('save_event')
            console.log(Event);


            $.ajax({
                url: '/calendar/save-event', // Укажите ваш URL обработчика
                type: 'POST',
                dataType: 'json',
                data: {
                    event: Event,
                    _csrf: csrfToken
                },
                success: function(response) {
                    console.log('success data');
                    console.log(response);
                    $('#createEventModal').modal('hide');
                },
                error: function(xhr) {
                   console.log(xhr);
                }
            });
        });



    });

</script>

