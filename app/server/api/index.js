import express from 'express'
import auth from './auth'

let app = express()
const router = express.Router()

router.use((req, res, next) => {
  Object.setPrototypeOf(req, app.request)
  Object.setPrototypeOf(res, app.response)
  req.res = res
  res.req = req
  next()
})

// Add AUTH Routes
router.use(auth)

export default router
