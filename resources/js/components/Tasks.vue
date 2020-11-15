<template>
    <div>
        <h3>Vue + Laravel Task App</h3>
        <AddTask v-on:add-task="addTask" />
        <div class="input-group mb-3"></div>
        <div class="input-group mb-3">
            <button @click="changeOrder(1)" v-bind:class="{'btn-success': this.colorButton === 1}" type="submit" class="btn btn-outline-dark">Sort By Order</button>
            <button @click="sortByPriorityMethod(2)" v-bind:class="{'btn-success': this.colorButton === 2}" type="submit" class="btn btn-outline-dark" style="margin-left: 20px;">Sort By Priority</button>
            <button @click="sortByDoneMethod(3)" v-bind:class="{'btn-success': this.colorButton === 3}" type="submit" class="btn btn-outline-dark" style="margin-left: 20px;">Sort By Done</button>
        </div>
        <div v-if="tasks">
            <div class="mb-2 p-2" v-for="task in tasks" v-bind:key="task.id" style="background: rgba(200,200,200,1); border-radius: 5px;">
                <div style="display: inline-block; width: 30px; vertical-align: top;">
                    <input @click="updateDoneTask(task.done, task.id)" type="checkbox" v-bind:checked="task.done" style="width: 20px; height: 20px;">
                </div>
                <div style="display: inline-block; width: 60%; vertical-align: top;">
                    <h5>{{ task.title }}</h5>
                    <p>{{ task.body }}</p>


                    <!-- Modal -->
                    <div v-if="editButtonId === task.id" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Change Task</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input style="width: 100%;" type="text" v-model="task.title">
                                    <textarea style="width: 100%;" name="" v-model="task.body"></textarea>
                                    <input style="width: 100%;" type="number" v-model="task.priority" min="1" max="3">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button @click="updateTask(task.title, task.body, task.priority, task.id, task.changed)" type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button @click="changeEditButton(task.id)" data-toggle="modal" data-target="#exampleModal" type="submit" class="btn btn-dark float-right mr-2">Edit Task</button>
                    <button @click="deleteTask(task.id)" type="submit" class="btn float-right mr-2" style="background: red;">Delete Task</button>
                </div>
                <div style="display: inline-block; width: 100px; vertical-align: top;">
                    <p v-bind:class="{'bg-danger': task.priority === 1, 'bg-success': task.priority === 2, 'bg-warning': task.priority === 3}" class="text-light" style="border: 1px solid black; border-radius: 3px; padding: 5px; text-align: center;">{{task.priority === 1 ? 'High' : task.priority === 2 ? 'Middle' : 'Low'}}</p>
                    <p v-bind:class="{'bg-dark': task.changed, 'text-light': task.changed}" style="border: 1px solid black; border-radius: 3px; padding: 5px; text-align: center;">{{!!task.changed ? 'changed' : 'not touched'}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddTask from "./AddTask";

export default {
    name: "Tasks",
    components: {
      AddTask
    },
    data(){
        return {
            tasks: [],
            task: {
                id: '',
                user_id: '',
                title: '',
                body: '',
                priority: '',
                done: '',
                changed: ''
            },
            editButtonId: null,
            sortById: false,
            sortByDone: false,
            sortByPriority: false,
            colorButton: null,
            form: new Form({
                title: '',
                body: ''
            })
        }
    },
    created() {
        this.fetchTasks();
    },
    methods: {
      fetchTasks() {
        fetch('api/tasks').then(res => res.json()).then(data => {
            if (!!this.sortById){
                this.tasks = data.sort((a, b) => b.id - a.id);
            }else{
                this.tasks = data
            }

            console.log(this.tasks)
        });
      },
        addTask(newTask){
            const {title, body, priority} = newTask
            axios.post('api/task', {
                title,
                body,
                priority
            })
                .catch(err => console.log(err))

            this.fetchTasks();
        },
        deleteTask(id){
            axios.delete(`api/task/${id}`)
                .catch(err => console.log(err))

            this.fetchTasks();
        },
        editTask(id){
            axios.get(`api/task/${id}`)
                .then(res => res.data)
                .catch(err => console.log(err))
        },
        changeEditButton(id){
            this.editButtonId = id
        },
        updateTask(title, body, priority, id, changed){
            axios.post(`api/task/${id}`, {
                title: title,
                body: body,
                priority: priority,
                changed: true
            })
                .catch(err => console.log(err))

            this.fetchTasks();
        },
        updateDoneTask(done, id){
            axios.post(`api/task/${id}`, {
                done: !done
            })
                .catch(err => console.log(err))
        },
        changeOrder(color){
            this.fetchTasks();
            this.sortById = !this.sortById
            this.colorButton = color
        },
        sortByPriorityMethod(color) {
            fetch('api/tasks').then(res => res.json()).then(data => {
                if (!!this.sortByPriority) {
                    this.tasks = data.sort(function (a, b) {
                        let titleA = a.priority, titleB = b.priority
                        return titleA < titleB ? -1 : titleA > titleB ? 1 : 0
                    })
                } else {
                    this.tasks = data.sort(function (a, b) {
                        let titleA = a.priority, titleB = b.priority
                        return titleA > titleB ? -1 : titleA < titleB ? 1 : 0
                    })
                }
                this.sortByPriority = !this.sortByPriority
                this.colorButton = color
                });
        },
        sortByDoneMethod(color) {
            fetch('api/tasks').then(res => res.json()).then(data => {
                if (!!this.sortByDone) {
                    this.tasks = data.sort(function (a, b) {
                        let titleA = a.done.toString(), titleB = b.done.toString()
                        return titleA < titleB ? -1 : titleA > titleB ? 1 : 0
                    })
                } else {
                    this.tasks = data.sort(function (a, b) {
                        let titleA = a.done.toString(), titleB = b.done.toString()
                        return titleA > titleB ? -1 : titleA < titleB ? 1 : 0
                    })
                }
                this.sortByDone = !this.sortByDone
                this.colorButton = color
            });
        }
    }
}
</script>

<style scoped>

</style>
