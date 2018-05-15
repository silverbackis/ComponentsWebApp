import compression from 'compression'
import express from 'express'
import { Nuxt, Builder } from 'nuxt'
import bodyParser from 'body-parser'
import cookieParser from 'cookie-parser'
import helmet from 'helmet'
import session from 'express-session'
import MysqlSession from 'express-mysql-session'
import mysql from 'mysql'
import config from '../nuxt.config.js'

const logging = process.env.NODE_ENV === 'development'
import BWServerBase from '@bwstarter/server'
const BWServer = new BWServerBase(process.env)

const init = async function () {
  config.dev = process.env.NODE_ENV !== 'production'
  const host = process.env.HOST || '127.0.0.1'
  const port = process.env.PORT || 3000

  /**
   * SETUP SESSION STORE
   */
  const MySQLStore = MysqlSession(session)
  const mysqlOps = {
    host: 'mysql',
    port: 3306,
    user: process.env.MYSQL_USER,
    password: process.env.MYSQL_PASSWORD,
    database: process.env.MYSQL_DATABASE
  }
  const connection = mysql.createConnection(mysqlOps)
  const sessionStore = new MySQLStore({}, connection)
  let sessOps = {
    secret: process.env.COOKIE_SECRET,
    name: 'JS_SESSION',
    resave: false,
    saveUninitialized: false,
    proxy: true,
    cookie: {
      secure: !config.dev,
      httpOnly: true,
      maxAge: null,
      path: '/'
    },
    store: sessionStore
  }

  /**
   * CREATE APP SERVER
   */
  const app = express()

  app.set('port', port)
  app.disable('x-powered-by')
  app.set('trust proxy', !!config.dev)
  app.use(compression())
  app.use(bodyParser.urlencoded({ extended: false }))
  app.use(bodyParser.json())
  app.use(session(sessOps))
  app.use(helmet())
  app.use(cookieParser())

  /**
   * ADD SERVER ROUTES
   */
  const router = express.Router()
  router.use((req, res, next) => {
    Object.setPrototypeOf(req, app.request)
    Object.setPrototypeOf(res, app.response)
    req.res = res
    res.req = req
    next()
  })
  router.post('/login', (req, res = null) => {
    BWServer.login(req, res, process.env.API_URL, logging)
  })
  router.post('/logout', (req, res) => BWServer.logout(req, res))
  router.post('/refresh_token', (req, res) => BWServer.jwtRefresh(req, res))
  app.use(router)

  /**
   * INIT NUXT
   */
  const nuxt = new Nuxt(config)
  if (config.dev) {
    console.log('** Nuxt building...')
    const builder = new Builder(nuxt)
    builder.build().then(() => {
      console.log('** Nuxt build done')
    })
  }
  app.use(nuxt.render)

  // Listen the server
  const server = app.listen(port, host)
  return { app, server, config }
};

init()
  .then(({ server }) => {
    const address = server.address()
    console.log('Server listening on ' + address.address + ':' + address.port) // eslint-disable-line no-console
  })
  .catch((err) => {
    console.error('SERVER INIT ERROR', err)
  });
