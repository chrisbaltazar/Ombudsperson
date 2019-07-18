<template>
    <div class="col-12" >
        <div class="row my-4">
            <h3 class="col-4">Seguimiento del caso</h3>
            <div class="col-4 text-center">
                <button class="btn btn-secondary" @click="$emit('viewList')">Ver lista completa</button>
            </div>
            <div class="col-4" v-if="session('role') == 1">
                <button class="float-right btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mostrar: {{viewLabel}}</button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#"  @click.prevent="viewCalendar = 'ALL'">TODOS LOS EVENTOS</a>
                    <a class="dropdown-item" href="#"  @click.prevent="viewCalendar = 'THIS'">SOLO DE ESTE CASO</a>
                </div>
            </div>
        </div>

		<calendar-view
            :show-date = "showDate"
			:showEventTimes = "true"
            :events = "calendar"
            :timeFormatOptions = "timeFormat"
            @show-date-change = "setShowDate"
			@click-date = "newEvent"
            @click-event = "viewEvent" 
            style="min-height: 600px"
		></calendar-view>
    </div>
    
</template>

<script>
import moment from "moment";

export default {
  props: ["tracing", "data"],
  data() {
    return {
      all: [],
      events: [],
      viewCalendar: (this.session('role') == 1 ? 'ALL' : 'THIS'),
      showDate: new Date(), 
      timeFormat: new Intl.DateTimeFormat('en-US', {hour12: false, hour: 'numeric', minute: 'numeric'}).resolvedOptions() 
    };
  },
  computed: {
      calendar() {
          if(this.viewCalendar == 'ALL')
            return [...this.all, ...this.events];
          else
            return this.events;
      },
      viewLabel() {
          return this.viewCalendar == 'ALL' ? 'TODOS LOS EVENTOS' : 'SOLO DE ESTE CASO';
      }, 
      stillModify() {
          return (this.session('role') == 1 && this.data.status.id < 5);
      }, 
      lastEvent() {
          return moment(this.events[this.events.length - 1].startDate).toDate();
      }
  },
  methods: {
    setShowDate(d) {
      this.showDate = d;
    },
    newEvent(date) {
      if(this.stillModify){
          this.$emit("newEvent", date);
      }
    },
    viewEvent(event) {
      this.$emit("viewEvent", event);
    },
    setEvents() {
      this.events = this.tracing.map(t => {
        return {
          id: t.id,
          title: "<strong>" + t.type + "</strong> <small>" + t.subject + "</small>",
          startDate: moment(t.meeting_date).format('YYYY-MM-DD HH:mm'),
          classes: "calendar-event"
        };
      });
      
    }
  },
  watch: {
    tracing() {
      this.setEvents();
    } 
  },
  created() {
    this.$http.get('tracings').then(
        response => {
            this.all = response.data.events.filter(event =>  event.complaint_id != this.data.id ).map(e => {
                return {
                    id: e.id,
                    title: "<strong>" + e.type + "</strong> <small>" + e.subject + "</small>",
                    startDate: moment(e.meeting_date).format('YYYY-MM-DD HH:mm'),
                    classes: "calendar-all"
                }
            });
        }, 
        error => {
            this.Err(error);
        }
    )
    this.setEvents();
    this.setShowDate(this.lastEvent);
  }
};
</script>

<style >
.calendar-event {
  background: #d6c8f4;
  padding: 5px;
}
</style>
