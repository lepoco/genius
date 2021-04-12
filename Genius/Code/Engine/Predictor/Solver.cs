// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System;
using System.Collections.Generic;
using System.Linq;

namespace Genius.Code.Engine.Predictor
{
    /// <summary>
    /// Expert system engine looking for matching solutions.
    /// </summary>
    public sealed class Solver
    {
        private Engine.ExpertSystem _localExpertSystem;
        private List<KeyValuePair<int, int>> _references;
        private Dictionary<int, Predictor.Response> _responses;
        private Dictionary<int, int> _activeConditions;

        /// <summary>
        /// A dictionary containing all the conditions in the system, sorted by the most common.
        /// </summary>
        public Dictionary<int, int> ActiveConditions => this._activeConditions;

        public Solver()
        {

        }

        public void Build(Engine.ExpertSystem system = null)
        {
            if(system != null)
                this.LoadExpertSystem(system);

            this.BuildPredictionDictionary();
            this.BuildActiveConditionsList();
        }

        /// <summary>
        /// Loads into the class memory an instance of the expert system on which operations will be performed.
        /// </summary>
        /// <param name="system">Expert system class instance containing <see cref="Engine.Condition"/>'s and <see cref="Engine.Product"/>'s.</param>
        public void LoadExpertSystem(Engine.ExpertSystem system)
        {
            this._localExpertSystem = system;
        }

        /// <summary>
        /// Collects identifiers of <see cref="Engine.Condition"/>'s and <see cref="Engine.Product"/>'s from which operations will be performed.
        /// </summary>
        public void BuildPredictionDictionary()
        {
            if (this._localExpertSystem == null)
                throw new InvalidOperationException("Expert ExpertSystem was not loaded, unable to create Prediction Dictionary. Check the Solver.LoadExpertSystem().");

            if(this._localExpertSystem.KnowledgeBase.References == null || this._localExpertSystem.KnowledgeBase.References.Count == 0)
                throw new InvalidOperationException("The Expert ExpertSystem has loaded, but the References list is empty. Check ExpertSystem.KnowledgeBase.AddReference().");

            this._references = new List<KeyValuePair<int, int>>();

            for (int i = 0; i < this._localExpertSystem.KnowledgeBase.References.Count; i++)
                this._references.Add(new KeyValuePair<int, int>(this._localExpertSystem.KnowledgeBase.References[i].ProductId, this._localExpertSystem.KnowledgeBase.References[i].ConditionId));
        }

        public void BuildActiveConditionsList()
        {
            if (this._localExpertSystem == null)
                throw new InvalidOperationException("Expert ExpertSystem was not loaded, unable to create Conditions list. Check the Solver.LoadExpertSystem().");

            if (this._localExpertSystem.KnowledgeBase.References == null || this._localExpertSystem.KnowledgeBase.References.Count == 0)
                throw new InvalidOperationException("The Expert ExpertSystem has loaded, but the References list is empty. Check ExpertSystem.KnowledgeBase.AddReference().");

            this._activeConditions = new Dictionary<int, int> { };

            for (int i = 0; i < this._localExpertSystem.KnowledgeBase.References.Count; i++)
            {
                if (this._activeConditions.ContainsKey(this._localExpertSystem.KnowledgeBase.References[i].ConditionId))
                    this._activeConditions[this._localExpertSystem.KnowledgeBase.References[i].ConditionId] += 1;
                else
                    this._activeConditions.Add(this._localExpertSystem.KnowledgeBase.References[i].ConditionId, 1);
            }

            //Sort the answers by the most frequent ones.
            this._activeConditions = this._activeConditions.OrderBy(x => x.Value).ToDictionary(x => x.Key, x => x.Value);
        }

        /// <summary>
        /// Adds a new <see cref="Predictor.Response"/> to the <see cref="Engine.Condition"/> or updates it.
        /// </summary>
        /// <param name="conditionId">Identifier of the <see cref="Engine.Condition"/> that was answered.</param>
        /// <param name="response">The <see cref="Predictor.Response"/> to the <see cref="Engine.Condition"/> that was given.</param>
        public void AddResponse(int conditionId, Predictor.Response response)
        {
#if DEBUG
            System.Diagnostics.Debug.WriteLine("Condition #" + conditionId + " (" + this._localExpertSystem.KnowledgeBase.Conditions[conditionId].Name + "), was answered: " + response.ToString());
#endif
            if (this._responses == null)
                this._responses = new Dictionary<int, Response> { };

            //Substitute the response if an answer has already been given.
            if (this._responses.ContainsKey(conditionId))
                this._responses[conditionId] = response;
            else
                this._responses.Add(conditionId, response);
        }

        public void FlushConditionsWithResponses()
        {

        }
    }
}
