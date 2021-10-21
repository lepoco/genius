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
        private List<KeyValuePair</*product*/int, /*condition*/int>> _references;
        private Dictionary</*condition*/int, Predictor.Response> _responses;
        private Dictionary</*condition*/int, /*matches*/int> _activeConditions;

        /// <summary>
        /// Gets a dictionary containing all the conditions used in the system, sorted by the most common.
        /// </summary>
        public Dictionary<int, int> ActiveConditions => this._activeConditions;

        public Solver()
        {}

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
                throw new InvalidOperationException("Expert System was not loaded, unable to create Prediction Dictionary. Check the Solver.LoadExpertSystem().");

            if(this._localExpertSystem.KnowledgeBase.References == null || this._localExpertSystem.KnowledgeBase.References.Count == 0)
                throw new InvalidOperationException("The Expert System has loaded, but the References list is empty. Check ExpertSystem.KnowledgeBase.AddReference().");

            this._references = new List<KeyValuePair<int, int>>();

            for (int i = 0; i < this._localExpertSystem.KnowledgeBase.References.Count; i++)
                this._references.Add(new KeyValuePair<int, int>(this._localExpertSystem.KnowledgeBase.References[i].ProductId, this._localExpertSystem.KnowledgeBase.References[i].ConditionId));
        }

        /// <summary>
        /// Lists all <see cref="Engine.Condition"/>'s used in the system. Available later using <see cref="ActiveConditions"/>.
        /// </summary>
        public void BuildActiveConditionsList()
        {
            if (this._localExpertSystem == null)
                throw new InvalidOperationException("Expert System was not loaded, unable to create Conditions list. Check the Solver.LoadExpertSystem().");

            if (this._localExpertSystem.KnowledgeBase.References == null || this._localExpertSystem.KnowledgeBase.References.Count == 0)
                throw new InvalidOperationException("The Expert System has loaded, but the References list is empty. Check ExpertSystem.KnowledgeBase.AddReference().");

            this._activeConditions = new Dictionary<int, int> { };

            for (int i = 0; i < this._localExpertSystem.KnowledgeBase.References.Count; i++)
            {
                if (this._activeConditions.ContainsKey(this._localExpertSystem.KnowledgeBase.References[i].ConditionId))
                    this._activeConditions[this._localExpertSystem.KnowledgeBase.References[i].ConditionId] += 1;
                else
                    this._activeConditions.Add(this._localExpertSystem.KnowledgeBase.References[i].ConditionId, 1);
            }

            //Sort the answers by the most frequent ones.
            this._activeConditions = this._activeConditions.OrderByDescending(x => x.Value).ToDictionary(pair => pair.Key, pair => pair.Value);
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

        /// <summary>
        /// Gets the next <see cref="Engine.Condition"/> based on the <see cref="Predictor.Response"/>'s provided.
        /// </summary>
        public int NextCondition()
        {
            //list of matching references
            //IList<KeyValuePair<int, int>> matchingReferences = this._references.ToList();
            //Dictionary</*condition*/int, /*matches*/int> matchingConditions = new Dictionary<int, int> { };


            int[] trueResponses = this._responses.Keys.ToArray();
            IList<KeyValuePair<int, int>> matchingReferences = this._references.Where(reference => Array.Exists(trueResponses, element => element == reference.Value)).ToList();

            //int[] activeReferences = matchingReferences.ToArray();

            //foreach (KeyValuePair<int, Response> response in this._responses)
            //{
            //    //Select all conditions which have those conditions
            //    if (response.Value == Response.Confirmed)
            //    {
            //        //Get all Response that have a given Condition
            //        matchingReferences = matchingReferences.Where(v => /*condition*/v.Value == /*condition*/response.Key).ToList();
            //    }    
            //}

            //List<int> aReferences = new List<int> { };
            //foreach (KeyValuePair<int, int> reference in matchingReferences)
            //{
            //    if (!aReferences.Contains(reference.Key))
            //        aReferences.Add(reference.Key);
            //}

            //IList<KeyValuePair<int, int>> activeReferences = this._references.Where(
            //    reference => Array.Exists(aReferences.ToArray(), element => element == reference.Key)
            //).ToList();

            //List<int> activeReferences = new List<int> { };
            //foreach (KeyValuePair<int, int> reference in matchingReferences)
            //{
            //    if(!activeReferences.Contains(reference.Key))
            //    {
            //        activeReferences.Add(reference.Key);
            //    }
            //}

            //Remove responded
            //foreach (KeyValuePair<int, int> selectedConditions in this._activeConditions)
            //{
            //    //matchingReferences = matchingReferences.Where(item => item.Value != response.Key).ToList();
            //}

            //foreach (KeyValuePair<int, int> condition in this._activeConditions)
            //{
            //    foreach (KeyValuePair<int, int> reference in matchingReferences)
            //    {
            //        if(condition.Key == reference.Value)
            //        {
            //            foreach (KeyValuePair<int, Response> response in this._responses)
            //            {
            //                if(response.Key != condition.Key && !matchingConditions.ContainsKey(condition.Key))
            //                {
            //                    matchingConditions.Add(condition.Key, condition.Value);
            //                    continue;
            //                }
                            
            //            }
                        
            //        }
            //    }
            //}
#if DEBUG
            System.Diagnostics.Debug.WriteLine("Matching responses: " + matchingReferences.Count());
            //System.Diagnostics.Debug.WriteLine("Selected condition: " + matchingReferences.Count());
#endif

            //return matchingReferences.First().Value;
            return 2;
        }

        public void FlushConditionsWithResponses()
        {

        }
    }
}
