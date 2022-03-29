/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { Link } from 'react-router-dom';
import Loader from '../../common/Loader';
import RoutedComponent from '../../../common/RoutedComponent';
import withRouter from '../../../common/withRouter';
import IRouterProps from '../../../interfaces/IRouterProps';
import { IExpertPageState } from '../../../genius/interfaces/IExpertPageState';
import {
  Genius,
  ExpertSystem,
  IExpertSystem,
  IExpertProduct,
  ExpertProduct,
  ExpertCondition,
  IExpertCondition,
  ExpertRelations,
  IExpertRelations,
  ISolverResponse,
  SolverQuestion,
  ConditionType,
} from '../../../genius/Genius';

/**
 * Contains information about the current state of the expert system's solution.
 */
class SolverState {
  currentCondition: IExpertCondition = new ExpertCondition(0, 0);

  solvedProducts: IExpertProduct[] = [];

  confirming: IExpertCondition[] = [];

  negating: IExpertCondition[] = [];

  indifferent: IExpertCondition[] = [];
}

/**
 * Represents the variables contained in the component state.
 */
interface IExpertRunState extends IExpertPageState {
  currentQuestion?: string;

  isConditional?: boolean;

  isSolved?: boolean;
}

class System extends RoutedComponent<IExpertRunState> {
  public static displayName: string = System.name;

  private solverState: SolverState = new SolverState();

  /**
   * Binds local methods, assigns properties, and defines the initial state.
   * @param props Properties passed by the router.
   */
  public constructor(props: IRouterProps) {
    super(props);

    this.state = {
      isSolved: false,
      isConditional: false,
      currentQuestion: '',
      systemLoaded: false,
      id: 0,
      guid: '',
      version: '',
      name: '',
      description: '',
      type: '',
      question: '',
      createdAt: '',
      updatedAt: '',
      conditions: [],
      products: [],
      relations: [],
      productsCount: 0,
      conditionsCount: 0,
      relationsCount: 0,
    };
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
    const system = await Genius.Api.getSystemByGuid(
      this.router.params.guid ?? '',
      false,
      false,
      false,
    );

    this.setState({
      id: system.id ?? 0,
      version: system.version ?? '1.0.0',
      name: system.name ?? '',
      description: system.description ?? '',
      guid: system.guid ?? '',
      question: system.question ?? '',
      type: system.type ?? '',
      createdAt: system.createdAt ?? '',
      updatedAt: system.updatedAt ?? '',
      conditions: system.conditions ?? [],
      products: system.products ?? [],
      relations: system.relations ?? [],
      systemLoaded: true,
    });

    this.validateQuestion();

    await this.getFirstCondition();

    return true;
  }

  private validateQuestion(): void {
    this.setState({
      isConditional: this.state.question?.includes('{condition}') ?? false,
    });
  }

  private async getFirstCondition(): Promise<void> {
    let systemId: number = this.state.id ?? 0;

    // ERROR, something went wrong
    if (systemId < 1) {
      return;
    }

    this.solverState.confirming = [];
    this.solverState.negating = [];
    this.solverState.indifferent = [];

    await this.askQuestion();
  }

  private async askQuestion(): Promise<ISolverResponse> {
    let question = new SolverQuestion();

    question.multiple = true; // Always allow multiple results
    question.systemId = this.state.id ?? 0;
    question.confirming = this.solverState.confirming ?? [];
    question.negating = this.solverState.negating ?? [];
    question.indifferent = this.solverState.indifferent ?? [];

    let solverResponse = await Genius.Api.ask(question);

    let nextCondition: IExpertCondition =
      solverResponse.nextCondition ?? new ExpertCondition(0);
    let nextConditionId = nextCondition.id ?? 0;

    this.solverState.solvedProducts = solverResponse.products ?? [];

    if (this.solverState.solvedProducts.length > 0) {
      this.setState({ isSolved: true });

      return solverResponse;
    }

    if (nextConditionId > 0) {
      this.solverState.currentCondition = nextCondition;

      this.setCurrentCondition(nextCondition);

      return solverResponse;
    }

    return solverResponse;
  }

  private setCurrentCondition(condition: IExpertCondition): void {
    this.setState({ currentQuestion: condition.name });
  }

  private async handleSubmitClick(
    event: any,
    conditionType: ConditionType,
  ): Promise<void> {
    event.preventDefault();

    let currentCondition = this.solverState.currentCondition;

    if ((currentCondition.id ?? 0) > 0) {
      switch (conditionType) {
        case ConditionType.Confirming: {
          this.solverState.confirming.push(currentCondition);
          break;
        }
        case ConditionType.Negating: {
          this.solverState.negating.push(currentCondition);
          break;
        }
        default: {
          this.solverState.indifferent.push(currentCondition);
          break;
        }
      }
    }

    let solverResponse: ISolverResponse = await this.askQuestion();

    console.debug('\\System\\handleSubmitClick\\conditionType', conditionType);
    console.debug('\\System\\handleSubmitClick\\event', event);
    console.debug('\\System\\handleSubmitClick\\solverResponse', solverResponse);
  }

  private replaceQuestionCondition(question: string): string {
    return question.replace('{condition}', this.state.currentQuestion ?? '###');
  }

  private renderQuestionForm(): JSX.Element {
    return (
      <div className="col-12">
        <div className="-reveal">
          {this.state.isConditional ? (
            <h4 className="-font-secondary -fw-700 -pb-3">
              <span className="--current_condition -pattern">
                {this.replaceQuestionCondition(this.state.question ?? '')}
              </span>
            </h4>
          ) : (
            <div>
              <p>
                <strong>{this.state.question ?? ''}</strong>
              </p>
              <h4 className="-font-secondary -fw-700 -pb-3">
                <span className="--current_condition">{this.state.currentQuestion}</span>
              </h4>
            </div>
          )}
        </div>

        <div className="-reveal">
          <button
            type="button"
            onClick={e => this.handleSubmitClick(e, ConditionType.Confirming)}
            className="btn btn-dark btn-mobile -lg-mr-1">
            Yes
          </button>
          <button
            type="button"
            onClick={e => this.handleSubmitClick(e, ConditionType.Negating)}
            className="btn btn-outline-dark btn-mobile -lg-mr-1">
            No
          </button>
          <button
            type="button"
            onClick={e => this.handleSubmitClick(e, ConditionType.Indifferent)}
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
                Found {this.solverState.solvedProducts.length}{' '}
                {this.solverState.solvedProducts.length > 1 ? 'results' : 'result'}.
              </i>
            </strong>
          </p>
        </div>
        <div>
          {this.solverState.solvedProducts.map((singleProduct, i) => {
            return (
              <div key={singleProduct.id ?? 0}>
                <h4>{singleProduct.name ?? ''}</h4>
                {/* <span>
                  <i>
                    <small>{singleProduct.id ?? '0'}</small>
                  </i>
                </span> */}
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
    if ((this.state.id ?? 0) < 1) {
      return (
        <div>
          <p>The specified expert system could not be found</p>
          <Link to={'/dashboard/add'}>Add new expert system</Link>
        </div>
      );
    }

    let contents = this.state.isSolved ? this.renderResults() : this.renderQuestionForm();

    return (
      <div className="row">
        <div className="col-12 -mb-3">
          <h4 className="-font-secondary -fw-700">{this.state.name ?? ''}</h4>
          <p className="-reveal">{this.state.description ?? ''}</p>
        </div>

        {contents}
      </div>
    );
  }

  /**
   * The main method responsible for refreshing and rendering the view.
   */
  public render(): JSX.Element {
    const contents = !this.state.systemLoaded ? (
      <Loader center={false} />
    ) : (
      /**
       * Renders the content containing data downloaded from the server.
       */
      this.renderContent()
    );

    return <div className="dashboard container pt-5 pb-5">{contents}</div>;
  }
}

export default withRouter(System);
