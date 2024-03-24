# PHP (Slim Framework) REST API 


## Technologies Used

- PHP
- PDO
- Composer
- Slim Framework / psr7
- Postman
- MySQL

## Information
My project is in the slim4 folder:
</br></br><b>
www/slim4/whole project
</b>


## Prepared Requests

### Add Comments:
You can make a request to http://localhost/slim4/comments/add using the POST method with Postman. Sample data:

```json
[
    {
        "postId": 1,
        "id": 1,
        "name": "id labore ex et quam laborum",
        "email": "Eliseo@gardner.biz",
        "body": "laudantium enim quasi est quidem magnam voluptate ipsam eos\ntempora quo necessitatibus\ndolor quam autem quasi\nreiciendis et nam sapiente accusantium"
    },
    {
        "postId": 1,
        "id": 2,
        "name": "quo vero reiciendis velit similique earum",
        "email": "Jayne_Kuhic@sydney.com",
        "body": "est natus enim nihil est dolore omnis voluptatem numquam\net omnis occaecati quod ullam at\nvoluptatem error expedita pariatur\nnihil sint nostrum voluptatem reiciendis et"
    }
]
```

<br />

### Add Posts:

You can make a request to http://localhost/slim4/posts/add using the POST method with Postman. Sample data:

```json
[
    {
        "userId": 1,
        "id": 1,
        "title": "sunt aut facere repellat provident occaecati excepturi optio reprehenderit",
        "body": "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto"
    },
    {
        "userId": 1,
        "id": 2,
        "title": "qui est esse",
        "body": "est rerum tempore vitae\nsequi sint nihil reprehenderit dolor beatae ea dolores neque\nfugiat blanditiis voluptate porro vel nihil molestiae ut reiciendis\nqui aperiam non debitis possimus qui neque nisi nulla"
    },
    {
        "userId": 1,
        "id": 3,
        "title": "ea molestias quasi exercitationem repellat qui ipsa sit aut",
        "body": "et iusto sed quo iure\nvoluptatem occaecati omnis eligendi aut ad\nvoluptatem doloribus vel accusantium quis pariatur\nmolestiae porro eius odio et labore et velit aut"
    }
]
```
<br />

### Show Comments:

You can make a request to http://localhost/slim4/comments using the GET method with Postman or just use that URL.

<br />

### Show Posts:

You can make a request to http://localhost/slim4/posts using the GET method with Postman or just use that URL.

<br />

### Show Comments Only for a Specific Post

With Postman you can use the GET method to make a request to http://localhost/slim4/posts/{post_id}/comments specifying the post_id or just use that URL.
