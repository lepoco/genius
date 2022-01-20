import { Component } from 'react';
import withRouter from './../common/withRouter';
import IRouterProps from './../interfaces/IRouterProps';
import IRouter from './../interfaces/IRouter';
import { Link } from 'react-router-dom';

class Footer extends Component<IRouterProps> {
  static displayName = Footer.name;

  router: IRouter;

  constructor(props: IRouterProps) {
    super(props);

    this.router = props.router;
  }

  render() {
    return (
      <section className="expanded-footer">
        <div className="container py-4">
          <div className="row">
            <div className="col-12 col-lg-4">
              <h4 className="-mb-4">Genius</h4>
            </div>

            <div className="col-12"></div>

            <div className="expanded-footer__list col-12">
              <ul className="list-inline">
                <li className="list-inline-item">
                  © {new Date().getFullYear()} dev.lepo.co
                </li>
                <li className="list-inline-item">
                  <Link to={'/terms'}>Website Terms</Link>
                </li>
                <li className="list-inline-item">
                  <Link to={'/legal'}>Legal Agreements</Link>
                </li>
                <li className="list-inline-item">
                  <Link to={'/privacy'}>Privacy Policy</Link>
                </li>
                <li className="list-inline-item">
                  <a
                    href="https://github.com/lepoco/Genius"
                    target="_blank"
                    rel="noreferrer noopener">
                    Source Code
                  </a>
                </li>
                <li className="list-inline-item">
                  <Link to={'/licenses'}>Licenses</Link>
                </li>
              </ul>
            </div>

            <div className="expanded-footer__bottom col-12">
              If you would like to find out more about which genius entity you
              receive services from, or if you have any other questions, please
              reach out to us via the help@genius.lepo.co email.
            </div>
          </div>
        </div>
      </section>
    );
  }
}

export default withRouter(Footer);
