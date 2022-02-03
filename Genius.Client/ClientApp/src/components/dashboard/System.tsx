/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import RoutedComponent from '../../common/RoutedComponent';
import withRouter from './../../common/withRouter';
import IRouterProps from './../../interfaces/IRouterProps';
import IExpertPageState from '../../genius/IExpertPageState';
import IExpertCondition from '../../genius/IExpertCondition';
import SolverQuestion from '../../genius/SolverQuestion';
import GeniusApi from '../../genius/GeniusApi';

class SolverState {
  confirming: IExpertCondition[] = [];

  negating: IExpertCondition[] = [];

  indifferent: IExpertCondition[] = [];
}

interface IExpertRunState extends IExpertPageState {
  currentQuestion?: string;
  isConditional?: boolean;
}

class System extends RoutedComponent<IExpertRunState> {
  public static displayName: string = System.name;

  private solverState: SolverState = new SolverState();

  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      isConditional: false,
      currentQuestion: '__{UNKOWN QUESTION}__',
      systemLoaded: false,
      systemId: 0,
      systemGuid: '',
      systemVersion: '',
      systemName: '',
      systemDescription: '',
      systemType: '',
      systemQuestion: '',
      systemCreatedAt: '',
      systemUpdatedAt: '',
      systemConditions: [],
      systemProducts: [],
      systemRelations: [],
    };
  }

  public componentDidMount(): void {
    this.populateExpertSystemData();
  }

  private validateQuestion(): void {
    this.setState({
      isConditional:
        this.state.systemQuestion?.includes('{condition}') ?? false,
    });
  }

  private async populateExpertSystemData(): Promise<void> {
    const system = await GeniusApi.getSystemByGuid(
      this.router.params.guid ?? '',
      true,
      true,
      true,
    );

    this.setState({
      systemId: system.systemId ?? 0,
      systemVersion: system.systemVersion ?? '1.0.0',
      systemName: system.systemName ?? '',
      systemDescription: system.systemDescription ?? '',
      systemGuid: system.systemGuid ?? '',
      systemQuestion: system.systemQuestion ?? '',
      systemType: system.systemType ?? '',
      systemCreatedAt: system.systemCreatedAt ?? '',
      systemUpdatedAt: system.systemUpdatedAt ?? '',
      systemConditions: system.systemConditions ?? [],
      systemProducts: system.systemProducts ?? [],
      systemRelations: system.systemRelations ?? [],
      systemLoaded: true,
    });

    this.validateQuestion();
  }

  private async askQuestion(): Promise<void> {
    let question = new SolverQuestion();
    question.multiple = true; // Always allow multiple results
    question.systemId = this.state.systemId ?? 0;
    question.confirming = this.solverState.confirming ?? [];
    question.negating = this.solverState.negating ?? [];
    question.indifferent = this.solverState.indifferent ?? [];

    let solverResponse = await GeniusApi.ask(question);

    console.debug('\\System\\handleSubmit\\solverResponse', solverResponse);
  }

  private async handleSubmit(event): Promise<void> {
    event.preventDefault();

    this.askQuestion();
  }

  private replaceQuestionCondition(question: string): string {
    return question.replace('{condition}', 'replacedCON');
  }

  private renderSystemView(): JSX.Element {
    if ((this.state.systemId ?? 0) < 1) {
      return (
        <div>
          <p>The specified expert system could not be found</p>
          <Link to={'/dashboard/add'}>Add new expert system</Link>
        </div>
      );
    }

    return (
      <div className="row">
        <div className="col-12 -mb-3">
          <h4 className="-font-secondary -fw-700 -reveal">
            {this.state.systemName ?? ''}
          </h4>
          <p className="-reveal">{this.state.systemDescription ?? ''}</p>
        </div>

        <div className="col-12 -reveal">
          {this.state.isConditional ? (
            <h4 className="-font-secondary -fw-700 -pb-3">
              <span className="--current_condition -pattern">
                {this.replaceQuestionCondition(this.state.systemQuestion ?? '')}
              </span>
            </h4>
          ) : (
            <div>
              <p>
                <strong>{this.state.systemQuestion ?? ''}</strong>
              </p>
              <h4 className="-font-secondary -fw-700 -pb-3">
                <span className="--current_condition">
                  {this.state.currentQuestion}
                </span>
              </h4>
            </div>
          )}
        </div>

        <div className="col-12 -reveal">
          <form id="answer" method="POST" onSubmit={this.handleSubmit}>
            <button
              type="submit"
              id="submit_yes"
              name="submit_yes"
              value="yes"
              className="-yes btn btn-dark btn-mobile -lg-mr-1">
              Yes
            </button>
            <button
              type="submit"
              id="submit_no"
              name="submit_no"
              value="no"
              className="-no btn btn-outline-dark btn-mobile -lg-mr-1">
              No
            </button>
            <button
              type="submit"
              id="submit_dontknow"
              name="submit_dontknow"
              value="dontknow"
              className="-dontknow btn btn-outline-dark btn-mobile">
              I do not know
            </button>
          </form>
        </div>
      </div>
    );
  }

  public render(): JSX.Element {
    let contents = !this.state.systemLoaded ? (
      <p>
        <em>Loading...</em>
      </p>
    ) : (
      this.renderSystemView()
    );

    return <div className="dashboard container pt-5 pb-5">{contents}</div>;
  }
}

export default withRouter(System);
