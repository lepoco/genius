/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import { ToastProvider } from '../../common/Toasts';
import Loader from '../../common/Loader';
import RoutedComponent from '../../../common/RoutedComponent';
import withRouter from '../../../common/withRouter';
import IRouterProps from '../../../interfaces/IRouterProps';
import {
  Genius,
  IExpertSystem,
  ExpertSystem,
  IExpertProduct,
  ExpertCondition,
  IExpertCondition,
  ISolverResponse,
  SolverQuestion,
} from '../../../genius/Genius';

/**
 * Represents the variables contained in the component state.
 */
interface ISolverState {
  contentLoaded: boolean;
  isConditionalQuestion: boolean;
  isSolved: boolean;
  selectedSystemGuid: string;
  selectedSystem: IExpertSystem;
  selectedCondition: IExpertCondition;
  solvedProducts: IExpertProduct[];
}

/**
 * Stores the stateless memory of a given expert system solution.
 */
class SolverMemory {
  public confirming: IExpertCondition[] = [];
  public negating: IExpertCondition[] = [];
  public indifferent: IExpertCondition[] = [];
}

class Solver extends RoutedComponent<ISolverState> {
  public static displayName: string = Solver.name;

  private solverMemory: SolverMemory;

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: IRouterProps) {
    super(props);

    this.solverMemory = new SolverMemory();

    this.state = {
      contentLoaded: false,
      isConditionalQuestion: false,
      isSolved: false,
      selectedSystemGuid: props.router.params?.guid ?? '',
      solvedProducts: [],
      selectedSystem: new ExpertSystem(),
      selectedCondition: new ExpertCondition(),
    };

    this.submitButtonOnClick = this.submitButtonOnClick.bind(this);
  }

  /**
   * Called immediately after a component is mounted. Setting state here will trigger re-rendering.
   */
  public async componentDidMount(): Promise<boolean> {
    return await this.populateData();
  }

  /**
   * Asynchronously gets data from the server.
   */
  private async populateData(): Promise<boolean> {
    const system = await Genius.Api.getSystemByGuid(this.state.selectedSystemGuid);

    let conditionalQuestion = false;

    if (system.question.includes('{condition}')) {
      conditionalQuestion = true;
    }

    this.setState({
      selectedSystem: system,
      isConditionalQuestion: conditionalQuestion,
      contentLoaded: true,
    });

    await this.askQuestion();

    return true;
  }

  private async askQuestion(): Promise<ISolverResponse> {
    const solverQuestion = new SolverQuestion(
      this.state.selectedSystem.id,
      true,
      this.solverMemory.confirming,
      this.solverMemory.negating,
      this.solverMemory.indifferent,
    );

    const solverResponse = await Genius.Solver.ask(solverQuestion);

    if (solverResponse.products.length > 0) {
      this.setState({ isSolved: true, solvedProducts: solverResponse.products });

      return solverResponse;
    }

    if (solverResponse.nextCondition.id > 0) {
      this.setState({
        selectedCondition: solverResponse.nextCondition,
      });
    }

    return solverResponse;
  }

  private async submitButtonOnClick(
    event: React.MouseEvent<HTMLButtonElement, MouseEvent>,
    conditionType: Genius.ConditionType,
  ): Promise<boolean> {
    event.preventDefault();

    if (
      this.solverMemory.confirming.filter(
        con => con.id === this.state.selectedCondition.id,
      ).length > 0 ||
      this.solverMemory.negating.filter(con => con.id === this.state.selectedCondition.id)
        .length > 0 ||
      this.solverMemory.indifferent.filter(
        con => con.id === this.state.selectedCondition.id,
      ).length > 0
    ) {
      ToastProvider.show(
        'Error',
        'This condition has already appeared in the results once. The question will be asked again.',
      );

      await this.askQuestion();

      return false;
    }

    switch (conditionType) {
      case Genius.ConditionType.Confirming:
        this.solverMemory.confirming.push(this.state.selectedCondition);
        break;

      case Genius.ConditionType.Negating:
        this.solverMemory.negating.push(this.state.selectedCondition);
        break;

      case Genius.ConditionType.Indifferent:
        this.solverMemory.indifferent.push(this.state.selectedCondition);
        break;
    }

    const solverResponse: ISolverResponse = await this.askQuestion();

    console.debug('\\Solver\\submitButtonOnClick\\memory', this.solverMemory);
    console.debug('\\Solver\\submitButtonOnClick\\solverResponse', solverResponse);

    return true;
  }

  private replaceQuestionCondition(question: string): string {
    return question.replace('{condition}', this.state.selectedCondition.name ?? '###');
  }

  private renderQuestionForm(): JSX.Element {
    return (
      <div className="col-12">
        <div className="-reveal">
          {this.state.isConditionalQuestion ? (
            <h4 className="-font-secondary -fw-700 -pb-3">
              <span className="--current_condition -pattern">
                {this.replaceQuestionCondition(this.state.selectedSystem.question ?? '')}
              </span>
            </h4>
          ) : (
            <div>
              <p>
                <strong>{this.state.selectedSystem.question ?? ''}</strong>
              </p>
              <h4 className="-font-secondary -fw-700 -pb-3">
                <span className="--current_condition">
                  {this.state.selectedCondition.name}
                </span>
              </h4>
            </div>
          )}
        </div>

        <div className="-reveal">
          <button
            type="button"
            onClick={e => this.submitButtonOnClick(e, Genius.ConditionType.Confirming)}
            className="btn btn-dark btn-mobile -lg-mr-1">
            Yes
          </button>
          <button
            type="button"
            onClick={e => this.submitButtonOnClick(e, Genius.ConditionType.Negating)}
            className="btn btn-outline-dark btn-mobile -lg-mr-1">
            No
          </button>
          <button
            type="button"
            onClick={e => this.submitButtonOnClick(e, Genius.ConditionType.Indifferent)}
            className="btn btn-outline-dark btn-mobile">
            I do not know
          </button>
        </div>
      </div>
    );
  }

  private renderResults(): JSX.Element {
    return (
      <div className="col-12">
        <div>
          <p>
            <strong>
              <i>
                {'Found ' +
                  this.state.solvedProducts.length +
                  ' ' +
                  (this.state.solvedProducts.length > 1 ? 'results' : 'result') +
                  '.'}
              </i>
            </strong>
          </p>
        </div>
        <div>
          {this.state.solvedProducts.map((singleProduct, i) => {
            return (
              <div key={singleProduct.id ?? 0}>
                <h4>{singleProduct.name ?? ''}</h4>
                {(singleProduct.description ?? '') === '' ? (
                  false
                ) : (
                  <p>{singleProduct.description}</p>
                )}
                {(singleProduct.notes ?? '') === '' ? (
                  false
                ) : (
                  <div>
                    <span>
                      <strong className="-font-secondary -fw-700">Notes:</strong>
                    </span>
                    <p>{singleProduct.notes}</p>
                  </div>
                )}

                {i > 0 ? <hr /> : false}
              </div>
            );
          })}
        </div>
        <div className="-pt-3">
          <button
            type="button"
            onClick={e => this.reload()}
            className="btn btn-outline-dark btn-mobile">
            Restart
          </button>
        </div>
      </div>
    );
  }

  /**
   * Renders the content containing data downloaded from the server.
   */
  private renderContent(): JSX.Element {
    if (this.state.selectedSystem.id < 1) {
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
          <h4 className="-font-secondary -fw-700">{this.state.selectedSystem.name}</h4>
          <p className="-reveal">{this.state.selectedSystem.description}</p>
        </div>

        {this.state.isSolved ? this.renderResults() : this.renderQuestionForm()}
      </div>
    );
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    return (
      <div className="dashboard container pt-5 pb-5">
        {!this.state.contentLoaded ? <Loader center={false} /> : this.renderContent()}
      </div>
    );
  }
}

export default withRouter(Solver);
