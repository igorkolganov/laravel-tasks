<template>
    <div>
        <form @submit="addTask">
            <div class="input-group mb-3">
                <input v-model="title" type="text" class="form-control" placeholder="Enter title of task" aria-label="Recipient's username" aria-describedby="basic-addon2">
            </div>
            <div class="input-group mb-3">
                <textarea v-model="body" class="form-control" placeholder="Enter text of task (manually)" aria-label="With textarea"></textarea>
            </div>
            <div class="input-group mb-3">
                <input v-model="priority" type="number" class="form-control" placeholder="Enter priority of task from 1 to 3" aria-label="Recipient's username" aria-describedby="basic-addon2" min="1" max="3">
            </div>
            <button type="submit" class="btn btn-primary float-right">Add Task</button>
        </form>
    </div>
</template>

<script>
export default {
    name: "AddTask",
    data() {
        return{
            title: '',
            body: '',
            priority: ''
        }
    },
    methods: {
        addTask(e){
            e.preventDefault()
            const newTask = {
                title: this.title,
                body: this.body,
                priority: this.priority
            }
            this.$emit('add-task', newTask)
            this.title = ''
            this.body = ''
            this.priority = ''
        }
    }
}
</script>

<style scoped>

</style>
