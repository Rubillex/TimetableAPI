<template>
<div>
    <button @click="Search">
        Search
    </button>
    {{ searchResult }}
    <button @click="Get">
        Get
    </button>
    {{ searchGet }}
</div>
</template>

<script>
export default {
    name: "IndexComponent.vue",
    data: () => {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            searchResult: '',
            searchGet: ''
        }
    },
    methods: {
        async Search(){
            await axios.post('/api/searchTimetable/' + 58 + '/' + 'students')
            .then(response => this.searchResult = response.data)
        },

        async Get(){
            await axios.post('/api/getTimetable/' + 'lecturers' + '/' + "Уланов П.Н. (ст.пр.)" + '/' + '0' + '/' + '20220502' + '/' + '20220508/')
                .then(response => this.searchGet = response.data)
        }
    }
}
</script>

<style scoped>

</style>
