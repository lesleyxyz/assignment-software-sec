class CoursesAPIClient {
    constructor(token) {
        this.token = token;
        this.api = location.origin + "/api/courses"
    }

    async create(body){
        return await this.send("", "POST", body)
    }

    async read(id = null){
        if(id === null)
            return await this.send()
        else
            return await this.send(id)
    }

    async update(id, body){
        return await this.send(id, "PUT", body)
    }

    async delete(id){
        return await this.send(id, "DELETE")
    }

    send(endpoint = "", method = "GET", body = null) {
        let options = {
            method,
            headers: {
                'Authorization': 'Bearer ' + this.token,
                'Content-Type': 'application/json'
            },
        };

        if(body !== null){
            options = {
                ...options,
                body: JSON.stringify(body)
            }
        }

        return fetch(this.api + "/" + endpoint, options).then(r => r.json());
    }
}
