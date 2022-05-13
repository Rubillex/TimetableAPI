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
            await axios.post('http://194.67.74.159/api/searchTimetable/' + 585 + '/' + 'students')
            .then(response => this.searchResult = response.data)
        }
    }
}
</script>

<style scoped>

</style>
