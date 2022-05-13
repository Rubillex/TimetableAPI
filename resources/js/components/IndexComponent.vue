<template>
<div>
    <button @click="Search">
        Search
    </button>
    {{ searchResult }}
</div>
</template>

<script>
export default {
    name: "IndexComponent.vue",
    data: () => {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            searchResult: ''
        }
    },
    methods: {
        async Search(){
            await axios.post('/api/searchTimetable/' + 585 + '/' + 'students')
            .then(response => this.searchResult = response.data)
        }
    }
}
</script>

<style scoped>

</style>
