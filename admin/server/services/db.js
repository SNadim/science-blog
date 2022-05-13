const mysql = require('mysql');

const config = require('../config');

async function query(sql, params) {

    const connection = await mysql.createConnection(config.db);
   return await connection.query(sql, params, (err,result)=>{
        if(!err) {
            return "Aamar shonar Bangla";
        } else {
            res.status(500).send("Server site error!");
        }
    });

}

module.exports = {
    query
}