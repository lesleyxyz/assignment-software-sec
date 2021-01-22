<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div id="tokenForm" class="position-absolute">
                    <div class="form-inline position-relative" style="left: 40%">
                        <label for="token" class="mr-2">API Token:</label>
                        <input name="token" style="width: 25vw" class="form-control" type="text" id="token"/>
                        <button onclick="tokenDone()" class="btn btn-primary">Continue</button>
                    </div>
                    <span>No API Token? </span><a href="/user/api-tokens">Click here!</a>
                </div>
                <div id="otherForm" class="p-6 sm:px-20 bg-white border-b border-gray-200" style="display: none">
                    <div>
                        <h1 class="text-center">Courses</h1>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Teacher</th>
                                <th>Location</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody id="tbody"></tbody>
                        </table>
                    </div>
                    <div class="row">
                        <b>NOTE:</b><span>To update, use the same form to add a course. Then click the pencil button. If you don't want to update a field, remove the contents of the input field.A</span>
                        <div class="md-form col-md-4">
                            <input type="text" id="name" name="name" class="form-control validate">
                            <label for="name" class="">Course name</label>
                        </div>
                        <div class="md-form col-md-4">
                            <input type="text" id="teacher" name="teacher" class="form-control validate">
                            <label for="teacher" class="">Course teacher</label>
                        </div>
                        <div class="md-form col-md-4">
                            <input type="text" id="location" name="location" class="form-control validate">
                            <label for="location" class="">Course location</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="submit" onclick="read()" class="btn-primary form-control">Refresh</button>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" onclick="create()" class="btn-primary form-control">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/lib.js"></script>
    <script>
        let q = (e) => document.querySelector(e);
        let qa = (e) => document.querySelectorAll(e);

        let delBtn = `<button class="btn btn-danger course-delete">X</button>`
        let upBtn = `<button class="btn btn-info course-update">✎</button>`

        let client;

        function tokenDone(){
            q("#tokenForm").style.display = "none"
            q("#otherForm").style.display = "block"
            client = new CoursesAPIClient(q("#token").value)
            read();
        }

        async function create(){
            let newCourse = {
                name: q("#name").value,
                teacher: q("#teacher").value,
                location: q("#location").value,
            }

            await client.create(newCourse)

            await read();
        }

        async function read(){
            let courses = await client.read();
            q("#tbody").innerHTML = "";

            for(let course of courses){
                let tr = document.createElement("tr")
                let td1 = document.createElement("td")
                let td2 = document.createElement("td")
                let td3 = document.createElement("td")
                let td4 = document.createElement("td")
                let td5 = document.createElement("td")

                td1.innerText = course.id;
                td2.innerText = course.name;
                td3.innerText = course.teacher;
                td4.innerText = course.location;
                td5.innerHTML = delBtn + upBtn;

                td5.querySelector(".course-delete").addEventListener("click", deleteCourse);
                td5.querySelector(".course-delete").dataset.id = course.id;
                td5.querySelector(".course-update").addEventListener("click", update);
                td5.querySelector(".course-update").dataset.id = course.id;

                tr.appendChild(td1)
                tr.appendChild(td2)
                tr.appendChild(td3)
                tr.appendChild(td4)
                tr.appendChild(td5)

                q("#tbody").appendChild(tr)
            }
        }

        async function update(e){
            let id = +e.target.dataset.id;

            let course = {
                name: q("#name").value,
                teacher: q("#teacher").value,
                location: q("#location").value,
            }

            for(let key in course){
                course[key] = course[key].trim();
                if(!(course[key] && course[key] !== null && course[key] !== ""))
                    delete course[key];
            }

            await client.update(id, course);
            await read();
        }

        async function deleteCourse(e){
            let id = +e.target.dataset.id;
            await client.delete(id);
            await read();
        }
    </script>
</x-app-layout>
